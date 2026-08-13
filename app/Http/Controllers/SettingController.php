<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    private const SECTIONS = [
        'parish' => [
            'parish_name',
            'parish_address',
            'parish_contact',
            'parish_email',
        ],
        'donations' => [
            'gcash_number',
            'gcash_name',
            'qr_code',
            'bank_name',
            'bank_account_name',
            'bank_account_number',
        ],
        'leadership' => [
            'priest_name',
            'priest_role',
            'priest_quote',
            'priest_image',
            'assistant_priest_name',
            'assistant_priest_role',
            'assistant_priest_quote',
            'assistant_priest_image',
            'priest_years',
            'priest_contrib_short',
            'priest_contrib_full',
            'priest_contrib_confirmed',
            'priest_contrib_sources',
        ],
        'former_priests' => [
            'former_priests',
        ],
        'about_video' => [
            'about_video_url',
            'about_video_title',
            'about_video_description',
        ],
        'timeline' => [
            'parish_timeline',
        ],
        'gallery' => [
            'gallery_highlights_video',
        ],
        'email' => [
            'email_greeting',
            'email_closing',
            'email_signoff',
        ],
    ];

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings', compact('settings'));
    }

    public function updateSection(Request $request, string $section)
    {
        if (! isset(self::SECTIONS[$section])) {
            abort(404);
        }

        $validated = $request->validate($this->rulesForSection($section));

        $this->processFileUploads($request, $section, $validated);
        $this->processStructuredFields($request, $section, $validated);

        if ($section === 'donations' && $request->input('remove_qr')) {
            Setting::where('key', 'qr_code')->delete();
            unset($validated['qr_code']);
        }

        $this->persistValidated($validated, $section);

        Cache::forget('global_settings');
        Cache::forget('chatbot_parish_context');

        LogService::log('update_settings', null, ['section' => $section, 'keys' => array_keys($validated)]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Settings saved successfully!']);
        }

        return back()->with('success', 'Settings saved successfully!');
    }

    /** @deprecated Use updateSection instead */
    public function update(Request $request)
    {
        return $this->updateSection($request, 'parish');
    }

    private function rulesForSection(string $section): array
    {
        $rules = match ($section) {
            'parish' => [
                'parish_name' => 'nullable|string|max:255',
                'parish_address' => 'nullable|string|max:500',
                'parish_contact' => 'nullable|array|max:10',
                'parish_contact.*' => 'nullable|string|max:50',
                'parish_email' => 'nullable|email|max:255',
            ],
            'donations' => [
                'gcash_number' => 'nullable|string|max:255',
                'gcash_name' => 'nullable|string|max:255',
                'qr_code' => 'nullable|image|max:10240',
                'bank_name' => 'nullable|string|max:255',
                'bank_account_name' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
            ],
            'leadership' => [
                'priest_name' => 'nullable|string|max:255',
                'priest_role' => 'nullable|string|max:255',
                'priest_quote' => 'nullable|string|max:500',
                'priest_image' => 'nullable|image|max:10240',
                'assistant_priest_name' => 'nullable|string|max:255',
                'assistant_priest_role' => 'nullable|string|max:255',
                'assistant_priest_quote' => 'nullable|string|max:500',
                'assistant_priest_image' => 'nullable|image|max:10240',
                'priest_years' => 'nullable|string|max:100',
                'priest_contrib_short' => 'nullable|string|max:500',
                'priest_contrib_full' => 'nullable|string|max:3000',
                'priest_contrib_confirmed' => 'nullable|boolean',
                'priest_contrib_sources' => 'nullable|string|max:3000',
            ],
            'former_priests' => [
                'former_priests' => 'nullable|array|max:20',
                'former_priests.*.name' => 'nullable|string|max:255',
                'former_priests.*.role' => 'nullable|string|max:255',
                'former_priests.*.years' => 'nullable|string|max:100',
                'former_priests.*.quote' => 'nullable|string|max:500',
                'former_priests.*.existing_image' => 'nullable|string|max:500',
                'former_priests.*.image' => 'nullable|image|max:10240',
                'former_priests.*.contrib_short' => 'nullable|string|max:500',
                'former_priests.*.contrib_full' => 'nullable|string|max:3000',
                'former_priests.*.contrib_confirmed' => 'nullable|boolean',
                'former_priests.*.contrib_sources' => 'nullable|string|max:3000',
            ],
            'about_video' => [
                'about_video_url' => 'nullable|string|max:500',
                'about_video_title' => 'nullable|string|max:255',
                'about_video_description' => 'nullable|string|max:1000',
            ],
            'timeline' => [
                'parish_timeline' => 'nullable|array|max:30',
                'parish_timeline.*.year' => 'nullable|string|max:10',
                'parish_timeline.*.badge' => 'nullable|string|max:50',
                'parish_timeline.*.title' => 'nullable|string|max:255',
                'parish_timeline.*.short' => 'nullable|string|max:2000',
                'parish_timeline.*.full' => 'nullable|string|max:2000',
            ],
            'gallery' => [
                'gallery_highlights_video' => 'nullable|string|max:500',
            ],
            'email' => [
                'email_greeting' => 'nullable|string|max:500',
                'email_closing' => 'nullable|string|max:500',
                'email_signoff' => 'nullable|string|max:500',
            ],
            default => [],
        };

        return $rules;
    }

    private function processFileUploads(Request $request, string $section, array &$validated): void
    {
        $disk = $this->publicSettingsDisk();

        if ($section === 'donations' && $request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->storePublicly('settings', $disk);
            Setting::updateOrCreate(['key' => 'qr_code'], ['value' => $path]);
            unset($validated['qr_code']);
        }

        if ($section === 'leadership') {
            if ($request->hasFile('priest_image')) {
                $path = $request->file('priest_image')->storePublicly('settings', $disk);
                Setting::updateOrCreate(['key' => 'priest_image'], ['value' => $path]);
                unset($validated['priest_image']);
            }
            if ($request->hasFile('assistant_priest_image')) {
                $path = $request->file('assistant_priest_image')->storePublicly('settings', $disk);
                Setting::updateOrCreate(['key' => 'assistant_priest_image'], ['value' => $path]);
                unset($validated['assistant_priest_image']);
            }
        }
    }

    private function processStructuredFields(Request $request, string $section, array &$validated): void
    {
        if ($section === 'parish' && isset($validated['parish_contact'])) {
            $validated['parish_contact'] = json_encode(array_values(array_filter(
                $validated['parish_contact'],
                fn ($v) => $v !== null && $v !== ''
            )));
        }

        if ($section === 'timeline' && isset($validated['parish_timeline'])) {
            $validated['parish_timeline'] = json_encode(array_values(array_filter(
                $validated['parish_timeline'],
                fn ($e) => ! empty(trim($e['year'] ?? '')) && ! empty(trim($e['title'] ?? ''))
            )));
        }

        if ($section === 'former_priests') {
            $rawEntries = $validated['former_priests'] ?? [];
            $entries = [];

            foreach ($rawEntries as $index => $entry) {
                $name = trim($entry['name'] ?? '');
                if ($name === '') {
                    continue;
                }

                $imagePath = $entry['existing_image'] ?? null;
                if ($request->hasFile("former_priests.{$index}.image")) {
                    $imagePath = $request->file("former_priests.{$index}.image")->storePublicly('settings/former-priests', $this->publicSettingsDisk());
                }

                $entries[] = [
                    'name' => $name,
                    'role' => trim($entry['role'] ?? '') ?: 'Parish Priest',
                    'years' => trim($entry['years'] ?? ''),
                    'quote' => trim($entry['quote'] ?? ''),
                    'image' => $imagePath,
                    'contrib_short' => trim($entry['contrib_short'] ?? ''),
                    'contrib_full' => trim($entry['contrib_full'] ?? ''),
                    'contrib_confirmed' => !empty($entry['contrib_confirmed']),
                    'contrib_sources' => trim($entry['contrib_sources'] ?? ''),
                ];
            }

            $validated['former_priests'] = json_encode(array_values($entries));
        }
    }

    private function persistValidated(array $validated, string $section): void
    {
        $fileKeys = ['qr_code', 'priest_image', 'assistant_priest_image'];

        foreach ($validated as $key => $value) {
            if (in_array($key, $fileKeys, true)) {
                continue;
            }
            if ($value !== null) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }
    }

    /**
     * Settings images must be publicly accessible (admin previews + public pages).
     * If the app is using the private `local` disk by default, fall back to `public`.
     */
    private function publicSettingsDisk(): string
    {
        $default = config('filesystems.default');

        return $default === 'local' ? 'public' : $default;
    }
}

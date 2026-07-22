<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'parish_name' => 'nullable|string|max:255',
            'parish_address' => 'nullable|string|max:500',
            'parish_contact' => 'nullable|array|max:10',
            'parish_contact.*' => 'nullable|string|max:50',
            'parish_email' => 'nullable|email|max:255',
            'gcash_number' => 'nullable|string|max:255',
            'gcash_name' => 'nullable|string|max:255',
            'qr_code' => 'nullable|image|max:10240',
            'priest_image' => 'nullable|image|max:10240',
            'priest_name' => 'nullable|string|max:255',
            'priest_role' => 'nullable|string|max:255',
            'priest_quote' => 'nullable|string|max:500',
            'assistant_priest_name' => 'nullable|string|max:255',
            'assistant_priest_image' => 'nullable|image|max:10240',
            'assistant_priest_role' => 'nullable|string|max:255',
            'assistant_priest_quote' => 'nullable|string|max:500',
            'gallery_highlights_video' => 'nullable|string|max:500',
            'email_greeting' => 'nullable|string|max:500',
            'email_closing' => 'nullable|string|max:500',
            'email_signoff' => 'nullable|string|max:500',
            'parish_timeline' => 'nullable|array|max:30',
            'parish_timeline.*.year' => 'nullable|string|max:10',
            'parish_timeline.*.badge' => 'nullable|string|max:50',
            'parish_timeline.*.title' => 'nullable|string|max:255',
            'parish_timeline.*.short' => 'nullable|string|max:2000',
            'parish_timeline.*.full' => 'nullable|string|max:2000',
        ]);

        if ($request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->store('settings');
            Setting::updateOrCreate(['key' => 'qr_code'], ['value' => $path]);
        }

        if ($request->hasFile('priest_image')) {
            $path = $request->file('priest_image')->store('settings');
            Setting::updateOrCreate(['key' => 'priest_image'], ['value' => $path]);
        }

        if ($request->hasFile('assistant_priest_image')) {
            $path = $request->file('assistant_priest_image')->store('settings');
            Setting::updateOrCreate(['key' => 'assistant_priest_image'], ['value' => $path]);
        }

        if (isset($validated['parish_contact'])) {
            $validated['parish_contact'] = json_encode(array_filter($validated['parish_contact'], fn($v) => $v !== null && $v !== ''));
        }

        if (isset($validated['parish_timeline'])) {
            $validated['parish_timeline'] = json_encode(array_values(array_filter($validated['parish_timeline'], fn($e) => !empty(trim($e['year'] ?? '')) && !empty(trim($e['title'] ?? '')))));
        }

        foreach ($validated as $key => $value) {
            if (!in_array($key, ['qr_code', 'priest_image', 'assistant_priest_image'])) {
                if ($value !== null) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            }
        }

        Cache::forget('global_settings');

        LogService::log('update_settings', null, ['keys' => array_keys($validated)]);
        return back()->with('success', 'Settings updated successfully!');
    }
}

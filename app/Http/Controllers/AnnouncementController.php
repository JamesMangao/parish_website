<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementController extends Controller
{
    public function publicIndex()
    {
        $featuredAnnouncement = Announcement::active()
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        $latestAnnouncements = Announcement::active()
            ->when($featuredAnnouncement, fn ($query) => $query->where('id', '!=', $featuredAnnouncement->id))
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('announcements.index', compact('featuredAnnouncement', 'latestAnnouncements'));
    }

    public function publicShow(Announcement $announcement)
    {
        if (!$announcement->is_published) {
            abort(404);
        }

        $recentAnnouncements = Announcement::active()
            ->where('id', '!=', $announcement->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('announcements.show', compact('announcement', 'recentAnnouncements'));
    }

    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateAnnouncement($request);

        $announcement = Announcement::create($validated);
        $this->syncFeaturedAnnouncement($announcement);
        Cache::forget('home_announcements');
        Cache::forget('chatbot_parish_context');
        LogService::log('create_announcement', $announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $this->validateAnnouncement($request);

        $announcement->update($validated);
        $this->syncFeaturedAnnouncement($announcement);
        Cache::forget('home_announcements');
        Cache::forget('chatbot_parish_context');
        LogService::log('update_announcement', $announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        LogService::log('delete_announcement', $announcement, ['title' => $announcement->title]);
        Cache::forget('home_announcements');
        Cache::forget('chatbot_parish_context');
        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }

    private function validateAnnouncement(Request $request): array
    {
        $categoryOptions = array_merge(
            Announcement::PREDEFINED_CATEGORIES,
            [Announcement::CATEGORY_OTHER]
        );

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:' . implode(',', $categoryOptions),
            'custom_category' => 'required_if:category,' . Announcement::CATEGORY_OTHER . '|nullable|string|max:100',
            'is_featured' => 'boolean',
            'is_recruitment' => 'boolean',
            'registration_link' => 'nullable|url|max:255',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        if ($validated['category'] === Announcement::CATEGORY_OTHER) {
            $validated['category'] = trim($validated['custom_category']);
        }

        unset($validated['custom_category']);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_recruitment'] = $request->boolean('is_recruitment');
        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }

    private function syncFeaturedAnnouncement(Announcement $announcement): void
    {
        if (!$announcement->is_featured) {
            return;
        }

        Announcement::where('id', '!=', $announcement->id)
            ->where('is_featured', true)
            ->update(['is_featured' => false]);
    }
}

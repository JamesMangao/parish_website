<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementController extends Controller
{
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_recruitment' => 'boolean',
            'registration_link' => 'nullable|url|max:255',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create($validated);
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_recruitment' => 'boolean',
            'registration_link' => 'nullable|url|max:255',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update($validated);
        Cache::forget('chatbot_parish_context');
        LogService::log('update_announcement', $announcement);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        LogService::log('delete_announcement', $announcement, ['title' => $announcement->title]);
        Cache::forget('chatbot_parish_context');
        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }
}

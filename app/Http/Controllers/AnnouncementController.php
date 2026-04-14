<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\LogService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('admin.announcements.index', compact('announcements'));
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
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create($validated);
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
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update($validated);
        LogService::log('update_announcement', $announcement);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        LogService::log('delete_announcement', $announcement, ['title' => $announcement->title]);
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}

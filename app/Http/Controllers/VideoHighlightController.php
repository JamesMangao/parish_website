<?php

namespace App\Http\Controllers;

use App\Models\VideoHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoHighlightController extends Controller
{
    public function index()
    {
        $highlights = VideoHighlight::orderBy('sort_order', 'asc')->get();
        return view('admin.gallery.highlights.index', compact('highlights'));
    }

    public function show(VideoHighlight $highlight)
    {
        return view('admin.gallery.highlights.show', compact('highlight'));
    }

    public function create()
    {
        return view('admin.gallery.highlights.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogv|max:102400', // 100MB
            'video_url' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $videoUrl = $request->video_url;

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('highlights', $filename);
            $videoUrl = $filename; // Store the filename/path
        }

        VideoHighlight::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoUrl,
            'is_published' => $request->has('is_published'),
            'sort_order' => VideoHighlight::count(),
        ]);

        return redirect()->route('admin.highlights.index')->with('success', 'Video Highlight created successfully!');
    }

    public function edit(VideoHighlight $highlight)
    {
        return view('admin.gallery.highlights.edit', compact('highlight'));
    }

    public function update(Request $request, VideoHighlight $highlight)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogv|max:102400', // 100MB
            'video_url' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $videoUrl = $request->video_url;

        if ($request->hasFile('video_file')) {
            // Delete old file if exists (using the raw database value)
            $oldPath = $highlight->getRawOriginal('video_path');
            if ($oldPath && !Str::startsWith($oldPath, ['http', 'www'])) {
                Storage::delete('highlights/' . $oldPath);
            }

            $file = $request->file('video_file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('highlights', $filename);
            $videoUrl = $filename;
        }

        $highlight->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoUrl ?: $highlight->getRawOriginal('video_path'),
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.highlights.index')->with('success', 'Video Highlight updated successfully!');
    }

    public function destroy(VideoHighlight $highlight)
    {
        $oldPath = $highlight->getRawOriginal('video_path');
        if ($oldPath && !Str::startsWith($oldPath, ['http', 'www'])) {
            Storage::delete('highlights/' . $oldPath);
        }
        $highlight->delete();
        return back()->with('success', 'Video Highlight deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $orders = $request->orders;
        foreach ($orders as $id => $order) {
            VideoHighlight::where('id', $id)->update(['sort_order' => $order]);
        }
        return response()->json(['status' => 'success']);
    }
}

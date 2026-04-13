<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GalleryAlbumController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('images')->orderBy('created_at', 'desc')->get();
        return view('admin.gallery.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $album = GalleryAlbum::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'is_published' => $request->has('is_published'),
            ]);

            if ($request->hasFile('images')) {
                $this->uploadMany($album, $request->file('images'));
            }

            return redirect()->route('admin.gallery.index')->with('success', 'Album created successfully!');
        } catch (\Exception $e) {
            if (isset($album)) $album->delete(); // Cleanup if failed partway
            return redirect()->back()->withInput()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function edit(GalleryAlbum $gallery)
    {
        $gallery->load('images');
        return view('admin.gallery.edit', ['album' => $gallery]);
    }

    public function update(Request $request, GalleryAlbum $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Album updated!');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        $gallery->delete(); // Images cascade delete via DB
        return back()->with('success', 'Album removed.');
    }

    public function addImages(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('images')) {
            $this->uploadMany($album, $request->file('images'));
        }

        return back()->with('success', 'Images added to album!');
    }

    public function removeImage(GalleryImage $image)
    {
        $image->delete();
        return back()->with('success', 'Image removed from album.');
    }

    private function uploadMany(GalleryAlbum $album, array $files)
    {
        set_time_limit(300);
        
        foreach ($files as $file) {
            $filename = Str::uuid() . '.' . $file->extension();
            
            try {
                // Save to local public storage instead of Supabase to avoid 42P01 errors
                $path = $file->storeAs('gallery', $filename, 'public');

                if ($path) {
                    $album->images()->create([
                        'title' => $file->getClientOriginalName(),
                        'storage_path' => $filename,
                        'is_published' => true,
                    ]);
                } else {
                    throw new \Exception('Failed to save file to local storage.');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Local Upload Exception: ' . $e->getMessage());
                throw $e;
            }
        }
    }
}

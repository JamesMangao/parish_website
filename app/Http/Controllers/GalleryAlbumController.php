<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Services\LogService;
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

    public function show(GalleryAlbum $gallery)
    {
        $gallery->load('images');
        return view('admin.gallery.show', ['album' => $gallery]);
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
            'featured_video_url' => 'nullable|string',
            'is_published' => 'boolean',
            'images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,ogv|max:102400', // Up to 100MB
        ]);

        try {
            $album = GalleryAlbum::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'featured_video_url' => $validated['featured_video_url'] ?? null,
                'is_published' => $request->has('is_published'),
            ]);

            if ($request->hasFile('images')) {
                $this->uploadMany($album, $request->file('images'));
            }

            LogService::log('create_album', $album, ['title' => $album->title]);
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
            'featured_video_url' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'featured_video_url' => $validated['featured_video_url'] ?? null,
            'is_published' => $request->has('is_published'),
        ]);

        LogService::log('update_album', $gallery, ['title' => $gallery->title]);
        return redirect()->route('admin.gallery.index')->with('success', 'Album updated!');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        LogService::log('delete_album', $gallery, ['title' => $gallery->title]);
        $gallery->delete(); // Images cascade delete via DB
        return back()->with('success', 'Album removed.');
    }

    public function addImages(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,ogv|max:102400',
        ]);

        if ($request->hasFile('images')) {
            $this->uploadMany($album, $request->file('images'));
        }

        LogService::log('add_album_images', $album, ['title' => $album->title, 'count' => $request->file('images') ? count($request->file('images')) : 0]);
        return back()->with('success', 'Images added to album!');
    }

    public function removeImage(GalleryImage $image)
    {
        $album = $image->album;
        LogService::log('remove_album_image', $album, ['image_title' => $image->title, 'image_id' => $image->id]);
        $image->delete();
        return back()->with('success', 'Image removed from album.');
    }

    private function uploadMany(GalleryAlbum $album, array $files)
    {
        set_time_limit(300);
        
        foreach ($files as $file) {
            $filename = Str::uuid() . '.' . $file->extension();
            
            try {
                // Save to the default disk (Supabase Cloud)
                $path = $file->storeAs('gallery', $filename);
 
                if ($path) {
                    $mime = $file->getMimeType();
                    $ext  = strtolower($file->getClientOriginalExtension());
                    
                    $isVideo = Str::startsWith($mime, 'video/') || in_array($ext, ['mp4', 'mov', 'ogv', 'avi', 'wmv', 'flv', 'mkv', 'webm']);
                    $type = $isVideo ? 'video' : 'image';
                    
                    $album->images()->create([
                        'title' => $file->getClientOriginalName(),
                        'storage_path' => $filename,
                        'type' => $type,
                        'is_published' => true,
                    ]);
                } else {
                    throw new \Exception('Failed to save file to cloud storage.');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Local Upload Exception: ' . $e->getMessage());
                throw $e;
            }
        }
    }
}

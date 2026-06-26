<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\GalleryAlbum;
use App\Models\VideoHighlight;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::where('is_published', true)
            ->withCount('images')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('gallery.index', compact('albums'));
    }

    public function publicIndex()
    {
        $albums = GalleryAlbum::where('is_published', true)
            ->with(['images' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->withCount('images')
            ->orderBy('created_at', 'desc')
            ->get();

        $latestItems = GalleryImage::whereHas('album', function($q) {
                $q->where('is_published', true);
            })
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        $highlights = VideoHighlight::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();
 
        return view('gallery', compact('albums', 'latestItems', 'highlights'));
    }

    public function publicAlbum(GalleryAlbum $album)
    {
        $album->load(['images' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        return view('album-show', compact('album'));
    }
}

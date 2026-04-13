<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function publicIndex()
    {
        $albums = GalleryAlbum::where('is_published', true)
            ->with(['images' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->withCount('images')
            ->orderBy('created_at', 'desc')
            ->get();

        $latestPhotos = GalleryImage::whereHas('album', function($q) {
                $q->where('is_published', true);
            })
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        return view('gallery', compact('albums', 'latestPhotos'));
    }

    public function publicAlbum(GalleryAlbum $album)
    {
        $album->load(['images' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        return view('album-show', compact('album'));
    }
}

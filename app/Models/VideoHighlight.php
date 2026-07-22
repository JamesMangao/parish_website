<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VideoHighlight extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_path',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getVideoUrlAttribute()
    {
        $value = $this->video_path;
        if (Str::startsWith($value, ['http', 'https', 'www'])) {
            return $value;
        }
        return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url('highlights/' . $value);
    }

    public function getThumbnailUrlAttribute()
    {
        $url = $this->video_url;

        if (preg_match('#(?:youtube\.com/(?:watch\?.*v=|embed/)|youtu\.be/)([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
            return 'https://vumbnail.com/' . $m[1] . '.jpg';
        }

        if (Str::startsWith($url, ['http', 'https', 'www'])) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url('highlights/' . $this->video_path);
    }

    public function getEmbedUrlAttribute()
    {
        $url = $this->video_url;

        if (preg_match('#youtube\.com/watch\?.*v=([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#youtu\.be/([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }

    public function getIsExternalAttribute()
    {
        return Str::startsWith($this->video_url, ['http', 'https', 'www']);
    }
}

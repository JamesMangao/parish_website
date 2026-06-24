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
}

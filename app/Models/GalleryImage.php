<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GalleryImage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'gallery_images';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'album_id',
        'title',
        'caption',
        'storage_path',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    public function getUrlAttribute()
    {
        return asset('storage/gallery/' . $this->storage_path);
    }
}

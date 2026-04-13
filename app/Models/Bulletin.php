<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'published_date',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];
}

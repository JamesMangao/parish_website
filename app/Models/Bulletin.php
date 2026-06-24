<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bulletin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'file_path',
        'published_date',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];
}

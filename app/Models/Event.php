<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'events';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'array',
        'is_published' => 'boolean',
    ];
}

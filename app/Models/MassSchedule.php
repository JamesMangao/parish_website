<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MassSchedule extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mass_schedules';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'mass_type',
        'day_of_week',
        'specific_date',
        'time',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'specific_date' => 'date',
        'day_of_week' => 'array',
        'time' => 'array',
    ];
}

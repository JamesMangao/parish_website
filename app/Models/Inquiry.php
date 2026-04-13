<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'full_name', 'email', 'phone', 'inquiry_type', 'preferred_date', 'message', 'status', 'accepted_at', 'reference_id'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'preferred_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($inquiry) {
            $inquiry->reference_id = (string) \Illuminate\Support\Str::uuid();
        });
    }
}

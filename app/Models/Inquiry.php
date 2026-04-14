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
            $year = date('Y');
            $count = Inquiry::whereYear('created_at', $year)->count() + 1;
            $inquiry->reference_id = 'INQ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }
}

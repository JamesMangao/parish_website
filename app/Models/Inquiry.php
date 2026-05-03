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
            
            // Find the latest reference ID for the current year
            $latest = Inquiry::where('reference_id', 'like', "INQ-{$year}-%")
                ->orderBy('reference_id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($latest) {
                // Extract the sequence number from the end (e.g., INQ-2026-0005 -> 5)
                $parts = explode('-', $latest->reference_id);
                $nextNumber = (int)end($parts) + 1;
            }

            $inquiry->reference_id = 'INQ-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}

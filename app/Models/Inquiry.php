<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'full_name', 'email', 'phone', 'inquiry_type', 'message', 'status', 'accepted_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];
}

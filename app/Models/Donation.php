<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'donor_name',
        'donor_email',
        'amount',
        'currency',
        'purpose',
        'message',
        'payment_method',
        'checkout_session_id',
        'paymongo_payment_id',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Get the formatted amount in PHP.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₱' . number_format($this->amount / 100, 2);
    }
}

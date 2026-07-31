<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MassIntention extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mass_intentions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'reference_number',
        'full_name',
        'email',
        'intention_type',
        'ai_suggested_type',
        'raw_message',
        'formatted_message',
        'preferred_date',
        'mass_time',
        'mass_schedule_id',
        'status',
        'rejection_reason',
        'payment_method',
        'reviewed_by',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function schedule()
    {
        return $this->belongsTo(MassSchedule::class, 'mass_schedule_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected static function booted()
    {
        static::creating(function ($intention) {
            if (!$intention->reference_number) {
                $intention->reference_number = static::generateReferenceNumber();
            }
        });
    }

    public static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $latest = static::where('reference_number', 'like', "INT-{$year}-%")
            ->orderBy('reference_number', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latest && preg_match('/INT-\d{4}-(\d+)/', $latest->reference_number, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        } else {
            // Fallback check counting current year if sequence pattern not matched
            $nextNumber = static::whereYear('created_at', $year)->count() + 1;
        }

        return 'INT-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

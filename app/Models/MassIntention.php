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
        'full_name',
        'intention_type',
        'ai_suggested_type',
        'raw_message',
        'formatted_message',
        'preferred_date',
        'mass_time',
        'mass_schedule_id',
        'status',
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
}

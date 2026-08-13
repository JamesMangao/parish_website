<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Announcement extends Model
{
    use HasFactory, HasUuids;

    public const PREDEFINED_CATEGORIES = [
        'Parish Life',
        'Liturgical',
        'Sacraments',
        'Formation',
    ];

    public const CATEGORY_OTHER = 'Other';

    protected $table = 'announcements';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'content',
        'category',
        'is_recruitment',
        'registration_link',
        'is_published',
        'is_featured',
        'published_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_recruitment' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

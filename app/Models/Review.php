<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'client_id',
        'caterer_id',
        'reviewer_name',
        'package_name',
        'title',
        'body',
        'rating',
        'status',
        'is_featured',
        'is_auto_featured',
        'caterer_reply',
        'replied_at',
        'reported_at',
        'report_reason',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_auto_featured' => 'boolean',
            'replied_at' => 'datetime',
            'reported_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeForCaterer(Builder $query, int $catererId): Builder
    {
        return $query->where('caterer_id', $catererId);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('status', 'public');
    }
}

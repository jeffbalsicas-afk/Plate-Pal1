<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'caterer_id',
        'package_id',
        'package_name',
        'package_price',
        'event_title',
        'event_date',
        'guests',
        'special_requests',
        'status',
        'decline_reason',
    ];

    protected $casts = [
        'event_date' => 'date',
        'package_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function getSelectedPackageNameAttribute(): ?string
    {
        return $this->package_name ?? $this->package?->name;
    }

    public function getEstimatedTotalAttribute(): ?float
    {
        if ($this->package_price === null) {
            return null;
        }

        return (float) $this->package_price;
    }
}

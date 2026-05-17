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
        'client_budget',
        'price_per_head',
        'final_price',
        'special_requests',
        'status',
        'decline_reason',
        'client_viewed_at',
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
        // If final_price is set (agreed price), use it
        if ($this->final_price !== null) {
            return (float) $this->final_price;
        }

        // If package_price is set, use it
        if ($this->package_price !== null) {
            return (float) $this->package_price;
        }

        // Otherwise calculate: guests × caterer's average price
        if ($this->guests && $this->caterer) {
            $avgPrice = ($this->caterer->price_min + $this->caterer->price_max) / 2;

            return (float) $this->guests * $avgPrice;
        }

        return null;
    }
}

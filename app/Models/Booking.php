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
        'client_budget' => 'decimal:2',
        'price_per_head' => 'decimal:2',
        'final_price' => 'decimal:2',
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
        if ($this->final_price !== null) {
            $finalPrice = (float) $this->final_price;

            if (
                $this->package_price === null
                && $this->client_budget !== null
                && $finalPrice <= $this->items_total
                && $this->calculated_total > $finalPrice
            ) {
                return $this->calculated_total;
            }

            return $finalPrice;
        }

        return $this->calculated_total;
    }

    public function getCalculatedTotalAttribute(): float
    {
        return $this->base_price + $this->items_total;
    }

    public function getBasePriceAttribute(): float
    {
        $basePrice = 0;

        if ($this->package_price !== null) {
            $basePrice = (float) $this->package_price;
        } elseif ($this->client_budget !== null) {
            $basePrice = (float) $this->client_budget;
        } elseif ($this->price_per_head !== null && $this->guests) {
            $basePrice = (float) $this->price_per_head * (int) $this->guests;
        } elseif ($this->guests && $this->caterer) {
            $avgPrice = ($this->caterer->price_min + $this->caterer->price_max) / 2;
            $basePrice = (float) $this->guests * $avgPrice;
        }

        return $basePrice;
    }

    public function getItemsTotalAttribute(): float
    {
        return (float) $this->bookingItems->sum(function ($item) {
            return ($item->item_price ?? 0) * ($item->quantity ?? 1);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $fillable = [
        'booking_id',
        'menu_item_id',
        'item_name',
        'item_type',
        'item_price',
        'quantity',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}

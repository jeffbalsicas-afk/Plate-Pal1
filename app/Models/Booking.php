<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'caterer_id', 'event_title', 'event_date', 'guests', 'status', 'decline_reason'];

    protected $casts = ['event_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }
}

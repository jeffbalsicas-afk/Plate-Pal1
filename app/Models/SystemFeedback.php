<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'type',
        'rating',
        'message',
        'page_url',
        'user_agent',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'caterer_id',
        'name',
        'image',
        'description',
        'price',
        'min_guests',
        'includes',
        'status',
        'category',
    ];

    protected $casts = [
        'includes' => 'array',
        'price' => 'decimal:2',
    ];

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    public function scopeForCaterer($query, $catererId)
    {
        return $query->where('caterer_id', $catererId)->latest();
    }
}

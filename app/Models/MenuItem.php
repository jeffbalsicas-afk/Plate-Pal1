<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'caterer_id',
        'name',
        'description',
        'price',
        'unit',
        'type',
        'category',
        'status',
        'image_path',
    ];

    protected $casts = [
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

    public function scopeMenuItems($query)
    {
        return $query->where('type', 'menu');
    }

    public function scopeAddOns($query)
    {
        return $query->where('type', 'addon');
    }
}

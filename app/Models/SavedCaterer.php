<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedCaterer extends Model
{
    protected $fillable = ['user_id', 'caterer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }
}

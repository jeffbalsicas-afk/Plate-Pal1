<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'caterer_id', 'body', 'is_read', 'sender'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caterer()
    {
        return $this->belongsTo(Caterer::class);
    }
}

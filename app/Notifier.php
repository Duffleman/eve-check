<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifier extends Model
{
    protected $fillable = [
        'type', 'value',
    ];

    protected $hidden = [
        'id', 'user_id', 'created_at', 'updated_at', 'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

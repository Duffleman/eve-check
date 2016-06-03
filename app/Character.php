<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'owner',
    ];

    protected $hidden = [
        'refresh_token',
        'owner',
        'created_at',
        'updated_at',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

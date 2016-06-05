<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transmission extends Model
{
    protected $fillable = ['notifier_id', 'status'];
}

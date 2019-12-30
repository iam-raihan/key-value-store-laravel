<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = [
        'key', 'value', 'expires_at'
    ];

    protected $dates = [
        'expires_at',
    ];

    protected $hidden = [
        'id'
    ];

    public $timestamps = false;
}

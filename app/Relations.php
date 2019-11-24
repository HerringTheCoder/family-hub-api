<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relations extends Model
{
    protected $fillable = [
        'type', 'stream_direction',
    ];

    protected $dates = ['created_at','updated_at'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

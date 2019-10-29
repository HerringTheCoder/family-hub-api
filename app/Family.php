<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
       'name','founder_id',
    ];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

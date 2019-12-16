<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'user_id', 'family_id','first_name', 'middle_name', 'last_name', 'day_of_birth', 'day_of_death', 'created_at', 'updated_at','avatar',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    
}

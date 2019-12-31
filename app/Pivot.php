<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    protected $fillable = [
        'user_id',
    ];

    
    protected $dates = ['created_at','updated_at'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $table ="pivot";

    //added because of error Illuminate\Database\QueryException: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'family-hub.pivots' doesn't exist
}

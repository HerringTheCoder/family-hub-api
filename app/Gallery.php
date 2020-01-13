<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = [
        'gallery',
    ];

    protected $fillable = [
        'author_id', 'filename','description','mime','original_filename',
    ];

    
    protected $dates = ['created_at','updated_at'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $table ="gallery";

    
    // added because of error: Illuminate\Database\QueryException: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'family-hub.galleries' doesn't exist 
    
}



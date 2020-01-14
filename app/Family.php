<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
       'founder_id','name',
    ];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function founder()
    {
        return $this->belongsTo('App\User', 'founder_id');
    }
    
}

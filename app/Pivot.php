<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Pivot extends Model
{
    protected $table = [
        'pivot',
    ];
    protected $fillable = [
        'user_id',
    ];
    
    protected $dates = ['created_at','updated_at'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Relation;
use DB;



class TreeService
{
    protected $json;
    protected $number;
    protected $parent;

    public function __construct() {
        $this->parent;
        $this->number = 0;
        $this->json = [];
        $this->licz = 0;
    }
    public function get()
    {
        
        $first = DB::table(Auth::User()->prefix.'_relations')
        ->where('parent_id','=', null)
        ->first();
        if($first){
            if($first->partner_2_id){
                $name1 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $first->partner_2_id)
                ->first();
                $name2 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $first->partner_1_id)
                ->first();
                $this->json[$this->number] =  [
                        'id' => $this->number,
                        'partnerId' => $this->number+1,
                        'name' => $name1->first_name.' '.$name1->last_name,
                        'birthDay' => $name1->day_of_birth,
                        'deathDay' => $name1->day_of_death,
                        'relation_id' => $first->id,
                        'user_id' => $first->partner_1_id,
                        'img' => $name1->avatar,
                        ];
                        $this->number++;
                
                $this->json[$this->number] =  [
                        'id' => $this->number,
                        'partnerId' => $this->number-1,
                        'name' => $name2->first_name.' '.$name2->last_name,
                        'birthDay' => $name2->day_of_birth,
                        'deathDay' => $name2->day_of_death,
                        'relation_id' => $first->id,
                        'user_id' => $first->partner_2_id,
                        'img' => $name2->avatar,
                        ];
                        $this->number++;
            }else{
                $name1 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $first->partner_1_id)
                ->first();
                $this->json[$this->number] =  [
                        'id' => $this->number,
                        'partnerId' => null,
                        'name' => $name1->first_name.' '.$name1->last_name,
                        'birthDay' => $name1->day_of_birth,
                        'deathDay' => $name1->day_of_death,
                        'relation_id' => $first->id,
                        'user_id' => $first->partner_1_id,
                        'img0' => $name1->avatar,
                        ];
                        $this->number++;
            }
        }else{
            return null;
        }
        
        $this->parent =  $this->number-1;
        TreeService::getChildren($first->id, $this->number-1);
        $json = $this->json;


        return $json;
        
    }


    public function getChildren($id, $parent)
    {
        
        $children = DB::table(Auth::User()->prefix.'_relations')
        ->where('parent_id','=', $id)
        ->get();
        
        foreach ($children as $child) {
            
            if($child->partner_2_id){
                $name1 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $child->partner_2_id)
                ->first();
                $name2 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $child->partner_1_id)
                ->first();
                $this->json[$this->number] =  [
                    'id' => $this->number,
                    'pid' => $parent,
                    'partnerId' => $this->number+1,
                    'name' => $name1->first_name.' '.$name1->last_name,
                    'birthDay' => $name1->day_of_birth,
                    'deathDay' => $name1->day_of_death,
                    'relation_id' => $child->id,
                    'user_id' => $child->partner_1_id,
                    'img' => $name1->avatar,
                    ];
                    $this->number++;
            
            $this->json[$this->number] =  [
                    'id' => $this->number,
                    'pid' => $parent,
                    'partnerId' => $this->number-1,
                    'name' => $name2->first_name.' '.$name2->last_name,
                    'birthDay' => $name2->day_of_birth,
                    'deathDay' => $name2->day_of_death,
                    'relation_id' => $child->id,
                    'user_id' => $child->partner_2_id,
                    'img' => $name2->avatar,
                    ];
                    $this->number++;
                    $this->parent =  $this->number-2;

                  
            }else{
                $name1 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $child->partner_1_id)
                ->first();
                $this->json[$this->number] =  [
                    'id' => $this->number,
                    'pid' => $parent,
                    'partnerId' => null,
                    'name' => $name1->first_name.' '.$name1->last_name,
                    'birthDay' => $name1->day_of_birth,
                    'deathDay' => $name1->day_of_death,
                    'relation_id' => $child->id,
                    'user_id' => $child->partner_1_id,
                    'img' => $name1->avatar,
                    ];
                    $this->number++;
                    $this->parent =  $this->number-1;
            }
            
            TreeService::getChildren($child->id, $this->parent);

        }
    }
    


       
}



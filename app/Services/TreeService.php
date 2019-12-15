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

    public function __construct() {
        $this->number = 0;
        $this->json = [];
    }
    public function get()
    {
        
        $first = DB::table(Auth::User()->prefix.'_relations')
        ->where('parent_id','=', null)
        ->first();
        if($first->partner_2_id){
            $name1 = DB::table(Auth::User()->prefix.'_members')
            ->where('user_id','=', $first->partner_2_id)
            ->first();
            $name2 = DB::table(Auth::User()->prefix.'_members')
            ->where('user_id','=', $first->partner_1_id)
            ->first();
            $this->json[$this->number] =  [
                    'id' => $first->id,
                    'name1' => $name1->first_name.' '.$name1->last_name,
                    'name2' => $name2->first_name.' '.$name2->last_name,
                    'img0' => $name1->avatar,
                    'img1' => $name2->avatar
                    ];
                    $this->number++;
        }else{
            $name1 = DB::table(Auth::User()->prefix.'_members')
            ->where('user_id','=', $first->partner_1_id)
            ->first();
            $this->json[$this->number] =  [
                    'id' => $first->id,
                    'name1' => $name1->first_name.' '.$name1->last_name,
                    'img0' => $name1->avatar,
                    ];
                    $this->number++;
        }

        TreeService::getChilds($first->id);
        $json = $this->json;


        return $json;
        
    }


    public function getChilds($id)
    {
        $childs = DB::table(Auth::User()->prefix.'_relations')
        ->where('parent_id','=', $id)
        ->get();

        foreach ($childs as $child) {

            if($child->partner_2_id){
                $name1 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $child->partner_2_id)
                ->first();
                $name2 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $child->partner_1_id)
                ->first();
                $this->json[$this->number] =  [
                        'id' => $child->id,
                        'name1' => $name1->first_name.' '.$name1->last_name,
                        'name2' => $name2->first_name.' '.$name2->last_name,
                        'img0' => $name1->avatar,
                        'img1' => $name2->avatar,
                        'pid' => $child->parent_id
                        ];
                        $this->number++;
            }else{
                $name1 = DB::table(Auth::User()->prefix.'_members')
                ->where('user_id','=', $child->partner_1_id)
                ->first();
                $this->json[$this->number] =  [
                        'id' => $child->id,
                        'name1' => $name1->first_name.' '.$name1->last_name,
                        'img0' => $name1->avatar,
                        'pid' => $child->parent_id
                        ];
                        $this->number++;
            }
        }
    }
    


       
}



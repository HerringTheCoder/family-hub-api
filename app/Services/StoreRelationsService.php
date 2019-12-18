<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Relation;
use DB;



class StoreRelationsService
{
    public function store($partner_1_id, $partner_2_id = null, $parent_id = null)
    {
        //check partner id 1 is already used in other record
        $first = DB::table(Auth::User()->prefix.'_relations')
        ->where('partner_1_id','=', $partner_1_id)
        ->get();

        $firstCheckOnSecond = DB::table(Auth::User()->prefix.'_relations')
        ->where('partner_2_id','=', $partner_1_id)
        ->get();

        //check partner id 2 is already used in other record
        $second = DB::table(Auth::User()->prefix.'_relations')
        ->where('partner_2_id','=', $partner_2_id)
        ->get();

        $secondCheckOnFirst = DB::table(Auth::User()->prefix.'_relations')
        ->where('partner_1_id','=', $partner_2_id)
        ->get();
        if(checkUser($partner_1_id) && checkUser($partner_2_id))
            if(!$first->isEmpty() && $firstCheckOnSecond->isEmpty()){
                
                return response()->json([
                    'message' => 'Your partner id 1 already used in other pair!'
                ], 200);  

            }elseif(($partner_2_id && (!$second->isEmpty() || !$secondCheckOnFirst->isEmpty()))){

                return response()->json([
                    'message' => 'Your partner id 2 already used in other pair!'
                ], 200);  

            }else{

                $relation = new Relation([
                    'partner_1_id' => $partner_1_id,
                    'partner_2_id' => $partner_2_id,
                    'parent_id' => $parent_id
                ]);
                $relation->setTable(Auth::User()->prefix.'_relations');
                $relation->save();

                return response()->json([
                    'message' => 'Success, data inserted!'
                ], 201);  
            }

       
    }


}

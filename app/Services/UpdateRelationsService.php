<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Relation;
use DB;



class UpdateRelationsService
{
    public function update($id, $partner_1_id = null, $partner_2_id = null, $parent_id = null)
    {
        //record to compare data
        $record = DB::table(Auth::User()->prefix.'_relations')
        ->where('id', $id)
        ->first();

        if(!$record){
            return response()->json([
                'message' => 'This record not exist!'], 200);
        }
        //check partner id 1 is exist in memebrs table
        $firstIsExist = DB::table(Auth::User()->prefix.'_members')
        ->where('user_id', $partner_1_id)
        ->first();

        //check partner id 1 is exist in memebrs table
        $secondIsExist = DB::table(Auth::User()->prefix.'_members')
        ->where('user_id', $partner_2_id)
        ->first();

        //check partner id 1 is already used in other record
        $first = DB::table(Auth::User()->prefix.'_relations')
        ->where('id','!=', $id)
        ->where('partner_1_id','=', $partner_1_id)
        ->get();

        $firstCheckOnSecond = DB::table(Auth::User()->prefix.'_relations')
        ->where('id','!=', $id)
        ->where('partner_2_id','=', $partner_1_id)
        ->get();

        //check partner id 2 is already used in other record
        $second = DB::table(Auth::User()->prefix.'_relations')
        ->where('id','!=', $id)
        ->where('partner_2_id','=', $partner_2_id)
        ->get();

        $secondCheckOnFirst = DB::table(Auth::User()->prefix.'_relations')
        ->where('id','!=', $id)
        ->where('partner_1_id','=', $partner_2_id)
        ->where('partner_2_id','!=', null)
        ->get();

        //parent id exist
        if(($record->parent_id == null) && ($parent_id == null)){
            $parent = true;
        }else{
            $parent = DB::table(Auth::User()->prefix.'_relations')
            ->where('id',$parent_id)
            ->get();

            if($parent->isEmpty()){
                $parent = false; 
            }else{
                $parent = true;
            }
        }
            if(($partner_1_id == null) && ($partner_2_id == null) && ($id != null)){
                if($record->parent_id != null){
                    DB::table(Auth::User()->prefix.'_relations')
                        ->where('parent_id', $record->id) 
                        ->update(['parent_id' =>  $record->parent_id]);
                        
                    DB::table(Auth::User()->prefix.'_relations')
                        ->where('id', $record->id) 
                        ->delete();

                    return response()->json([
                    'message' => 'Succes data updated!'], 200);
                }else{
                    return response()->json([
                        'message' => 'You can not delete head of family!'], 200);
                }
                
             }elseif((($firstIsExist == null) && ($partner_1_id != null)) || (($secondIsExist == null) && ($partner_2_id != null))){
                return response()->json([
                    'message' => 'Your partner id 1 or 2 is not exist!'
                ], 200);
            }elseif($partner_1_id == $partner_2_id){
                return response()->json([
                    'message' => 'Your partner id 1 and 2 is the same!'
                ], 200);  
            }elseif(!$parent){
                return response()->json([
                    'message' => 'Your parent id is not exist!'
                ], 200);  
            }elseif(!$first->isEmpty() && $firstCheckOnSecond->isEmpty()){
                
                return response()->json([
                    'message' => 'Your partner id 1 already used in other pair!'
                ], 200);  

            }elseif(($partner_2_id && (!$second->isEmpty() || !$secondCheckOnFirst->isEmpty()))){

                return response()->json([
                    'message' => 'Your partner id 2 already used in other pair!'
                ], 200);  

            }else{
                DB::table(Auth::User()->prefix.'_relations')
                ->where('id', $id)
                ->update([
                    'partner_1_id' => $partner_1_id,
                    'partner_2_id' => $partner_2_id,
                    'parent_id' =>  $parent_id]);

                if($partner_2_id){
                    $getRecord = DB::table(Auth::User()->prefix.'_relations')
                    ->where('id','!=', $id)
                    ->where('partner_1_id','=', $partner_2_id)
                    ->where('partner_2_id','=', null)
                    ->first();

                    if($getRecord){
                        DB::table(Auth::User()->prefix.'_relations')
                        ->where('parent_id', $getRecord->id) 
                        ->update(['parent_id' =>  $id]);
                        
                        DB::table(Auth::User()->prefix.'_relations')
                        ->where('id', $getRecord->id) 
                        ->delete();
                    }
                }

                if($record->partner_1_id != $partner_1_id && $record->partner_1_id != $partner_2_id && $record->partner_2_id && $record->partner_2_id != $partner_1_id && $record->partner_2_id != $partner_2_id){
                    
                    $relation = new Relation([
                        'partner_1_id' => $record->partner_1_id,
                        'partner_2_id' => $record->partner_2_id,
                        'parent_id' => $record->parent_id
                    ]);
                    $relation->setTable(Auth::User()->prefix.'_relations');
                    $relation->save();

                }else{

                    if($record->partner_1_id != $partner_1_id && $record->partner_1_id != $partner_2_id && $record->partner_1_id != null){
                        
                        DB::table(Auth::User()->prefix.'_relations')
                        ->where('id', $record->id) 
                        ->update([
                            'partner_1_id' =>  $record->partner_2_id,
                            'partner_2_id' => null]);
                    }


                    if($record->partner_2_id && $record->partner_2_id != $partner_1_id && ($record->partner_2_id != $partner_2_id && $partner_2_id != null)){

                        $relation = new Relation([
                            'partner_1_id' => $record->partner_2_id,
                            'partner_2_id' => null,
                            'parent_id' => $record->parent_id
                        ]);
                        $relation->setTable(Auth::User()->prefix.'_relations');
                        $relation->save();
                    }
                }

                return response()->json([
                    'message' => 'Success, data updated!'
                ], 201);  
            }

       
    }


}

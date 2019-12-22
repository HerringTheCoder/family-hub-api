<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreRelation;
use App\Http\Requests\UpdateRelation;
use App\Services\StoreRelationsService;
use App\Services\UpdateRelationsService;
use App\Services\TreeService;
use DB;

class RelationController extends Controller
{

    public function __construct(Relation $relation)
    {
        $this->relation = $relation;
    }

    public function index()
    {
        $this->relation->setTable(Auth::User()->prefix.'_relations');
        $relations = $this->relation->get();
        
        return response()->json([
            'message' => 'Success',
            'data' => $relations
        ], 201); 
        

    }

    public function store(StoreRelationsService $storeRelation, StoreRelation $request)
    {
       
        $storeRelation = $storeRelation->store($request->partner_1_id, $request->partner_2_id, $request->parent_id);

        return $storeRelation;
        
    }

    public function edit(Request $request)
    {
        $this->relation->setTable(Auth::User()->prefix.'_relations');
        $relation = $this->relation->get()->where('id',$request->id);
            return response()->json([
                'message' => 'Success',
                'data' => $relation
            ], 201);  
    }

    public function update(Request $request, UpdateRelationsService $updateRelation)
    {

        $updateRelation = $updateRelation->update($request->id, $request->partner_1_id, $request->partner_2_id, $request->parent_id);

        return $updateRelation;
        
    }

    // public function delete(Request $request)
    // {
        
        
    // }


    

    public function tree(TreeService $tree)
    {
        $tree = $tree->get();
        
        return response()->json([
            'message' => 'Success',
            'data' => $tree
        ], 201); 
        
    }

    

    public function getSingle()
    {
        $data = DB::table(Auth::User()->prefix.'_relations')
                ->where('partner_2_id', '=' , null) 
                ->get();
        
        return response()->json([
            'message' => 'Success',
            'data' => $data
        ], 201); 
        
    }
}

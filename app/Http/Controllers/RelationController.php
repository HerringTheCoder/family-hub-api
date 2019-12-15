<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreRelation;
use App\Services\StoreRelationsService;
use App\Services\TreeService;

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


    public function tree(TreeService $tree)
    {
        $tree = $tree->get();
        
        return response()->json([
            'message' => 'Success',
            'data' => $tree
        ], 201); 
        
    }

    public function store(StoreRelationsService $storeRelation, StoreRelation $request)
    {
       
        $storeRelation = $storeRelation->store($request);

        return $storeRelation;
        
    }

    public function edit(Request $request)
    {
       
    }

    public function update(Request $request)
    {
        
        
    }

    public function delete(Request $request)
    {
        
        
    }
}

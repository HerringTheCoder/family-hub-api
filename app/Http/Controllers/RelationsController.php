<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Relations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RelationRequest;

class RelationsController extends Controller
{
    public function index()
    {
        $relation = Relations::all();
        return response()->json([
            'message' => 'Success',
            'data' => $relation
        ], 201); 
        
    }

    public function store(RelationRequest $request)
    {
        $relation = new Relation([
            'type' => $request->type,
            'stream_direction' => $request->stream_direction
        ]);
        $relation->save();
        return response()->json([
            'message' => 'Success, data inserted!'
        ], 201);  
        
    }

    public function edit($id)
    {
        $relation = Relations::find($id);
        return response()->json([
            'message' => 'Success, found data!',
            'data' => $realation
        ], 201);  
    }

    public function update(RelationRequest $request, $id)
    {
        $relation = new Relations();
          
        $relation->where('id',$id)
                ->update([
                'type' => $request->type,
                'stream_direction' => $request->stream_direction
                ]);

        return response()->json([
            'message' => 'Success, data updated'], 201);
        
    }

    public function delete($id)
    {
        $relation = Relations::find($id);
        $relation->delete();
        return response()->json([
            'message' => 'Success, data deleted'], 201);
        
    }
}

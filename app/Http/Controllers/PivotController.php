<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Pivot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RelationRequest;


class PivotController extends Controller
{
    public function __construct(Pivot $pivot)
    {
        $this->pivot = $pivot;
    }

    public function index()
    {
        //Auth::User()->prefix = $request->prefix;
        $this->pivot->setTable(Auth::User()->prefix.'_pivot');
        $pivot = $this->pivot->where('user_id',Auth::User()->id)->get();
     
        return response()->json([
            'message' => 'Success',
            'count' => $pivot->count()
        ], 200 ); 
    }
}

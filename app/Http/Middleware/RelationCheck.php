<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RelationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $parent = '';
        
        $first = DB::table(Auth::User()->prefix.'_relations')
        ->where('partner_1_id', Auth::user()->id)
        ->first();

        if($first){
            $parent = DB::table(Auth::User()->prefix.'_relations')
            ->where('id',$request->id)
            ->where('parent_id', $first->id)
            ->first();
        }

        $second = DB::table(Auth::User()->prefix.'_relations')
        ->where('partner_2_id', Auth::user()->id)
        ->first();

        if($second){
            $parent = DB::table(Auth::User()->prefix.'_relations')
            ->where('id',$request->id)
            ->where('parent_id', $second->id)
            ->first();
        }


        $first = DB::table(Auth::User()->prefix.'_relations')
            ->where('id',$request->id)
            ->where('partner_1_id', Auth::user()->id)
            ->first();
        $second = DB::table(Auth::User()->prefix.'_relations')
            ->where('id',$request->id)
            ->where('partner_2_id', Auth::user()->id)
            ->first();
            

            if($request->id == $request->parent_id){
                return response()->json([
                    'message' => 'Sorry, parent id can not be the same like relation id!'], 403);
            }elseif($first || $second || $parent ||Auth::user()->isFounder){
                return $next($request);
            }else{
                return response()->json([
                    'message' => 'You are not authorized to this action!'], 403);
            }
    }
}

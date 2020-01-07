<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Auth;

class CheckFounder
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
        $founder = DB::table('families')
        ->where('founder_id','=', Auth::User()->id)
        ->first();

        if($founder){
            return $next($request);
        }else{
            return response()->json([
            'message' => 'You are not authorized to this action!'], 403);
        }
    }
}

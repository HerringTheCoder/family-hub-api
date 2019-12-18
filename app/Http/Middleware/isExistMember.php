<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;
class isExistMember
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
        $isFirst = DB::table(Auth::User()->prefix.'_members')
        ->where('user_id','=', $request->partner_1_id)
        ->first();

        $isSecond = DB::table(Auth::User()->prefix.'_members')
        ->where('user_id','=', $request->partner_2_id)
        ->first();

        if($request->partner_1_id && $request->partner_2_id){
            if($isFirst && $isSecond){
                return $next($request);
            }else{
                return response()->json([
                    'message' => 'That member not exist!'], 401);
            }
        }else{
            if($isFirst){
                return $next($request);
            }else{
                return response()->json([
                    'message' => 'That member not exist!'], 401);
            }
        }

    }
}

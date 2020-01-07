<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Auth;

class isFounder
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
            $family = DB::table('families')->where('name', '=', Auth::User()->prefix )->first();

            if($request->partner_id){
                $partner = DB::table(Auth::User()->prefix.'_relations')
                ->where('partner_1_id','=', $request->partner_id)
                ->first();
                
                $authRelationId = DB::table(Auth::User()->prefix.'_relations')
                ->where('partner_1_id', '=', Auth::User()->id)
                ->exists();
                
                if(($partner != null) && $authRelationId && ($partner->partner_2_id == null)){
                    return $next($request);
                }else{
                    $request->merge(['parent_id' => null]);
                    $request->merge(['partner_id' => null]);
                    return $next($request);
                }
            }else{
                $parent = DB::table(Auth::User()->prefix.'_relations')->where('partner_1_id',Auth::User()->id)->first();
                $request->merge(['parent_id' => $parent->id]);
                return $next($request);
            }
        }
    }
}

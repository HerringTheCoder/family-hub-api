<?php

namespace App\Http\Middleware;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Family;
use Closure;

class RequiredUser
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
        $isAdmin = Family::where('founder_id',Auth::user()->id)->first();
        if($isAdmin){
            return $next($request);
        }else{
            return response()->json([
                'message' => 'You are not admin, sorry!'], 401);
        }
    }
}

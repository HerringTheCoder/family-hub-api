<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Closure;

class CheckPrefix
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
        if(Schema::hasTable(Auth::User()->prefix.'_members')) {
            return $next($request);
        }
        
        return response()->json([
            'message' => 'This prefix is invalid'], 401);
        
    }
}

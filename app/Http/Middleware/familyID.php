<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Auth;

class familyID
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
        
        $family = DB::table('families')->where('name', '=', Auth::User()->prefix )->first();

        $request->request->add(['family_id' => $family->id]);
        return $next($request);
    }
}

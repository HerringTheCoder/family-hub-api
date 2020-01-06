<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


class NewsCheck
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
        $record = DB::table(Auth::User()->prefix.'_news')
            ->where('id',$request->id)
            ->where('author_id', Auth::user()->id)
            ->first();

            if($record ||  Auth::user()->isAdmin()){
                return $next($request);
            }else{
                return $next($request);
            }
    }
}

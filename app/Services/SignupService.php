<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Family;
use App\Member;
use Carbon\Carbon;
use App\Jobs\SendActivateLink;



class SignupService
{
    public function register($request) : void
    {
        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => Str::random(80),
            'prefix' => $request->name,           
            'type' => User::DEFAULT_TYPE,    
        ]);
        $user->save();

        
        SendActivateLink::dispatch($user);
        
        Log::channel()->notice("User created - id : ".$user->id);


    }

}

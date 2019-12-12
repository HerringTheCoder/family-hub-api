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
use App\Notifications\SignupActivate;



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
        $user->notify(new SignupActivate($user));
            
        $family = new Family([
            'name' => $request->name,
            'founder_id' => $user->id
        ]);
        $family->save();

        $service = new TableService();
        $service->addTables($request->name);
        
        $member = new Member([
            'user_id' => $user->id,
            'family_id' => $family->id
        ]);
        $member->setTable($request->name.'_members');
        $member->save();
        
        Log::channel()->notice("User created - id : ".$user->id);
        Log::channel()->notice("Member created - id : ".$member->id);
        Log::channel()->notice("Family created - id : ".$family->id);


    }

}

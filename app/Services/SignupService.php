<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Family;
use App\Member;
use Carbon\Carbon;
use App\Notifications\SignupActivate;



class SignupService
{
    public function register($request) : int
    {
        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => Str::random(80),
            'prefix' => $request->name
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

        return false;

    }

}

<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Member;
use Carbon\Carbon;
use App\Notifications\UserInvite;
use App\PasswordReset;
use App\services\StoreRelationAfterMemberCreateService;



class StoreMemberService
{
    public function store($request)
    {
        $password = Str::random(10);
        $prefix = Auth::User()->prefix;
        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($password),
            'activation_token' => Str::random(80),
            'prefix' => $prefix,           
            'type' => User::DEFAULT_TYPE
        ]);
        $user->save();

        $member = new Member([
            'user_id' => $user->id,
            'family_id' => $request->family_id,
            'first_name' => $request->first_name
        ]);
        $member->setTable(Auth::User()->prefix.'_members');
        $member->save();

        $user->notify(new UserInvite($user));
        
        if($request->partner_id || $request->parent_id){
            $relation = new StoreRelationAfterMemberCreateService();
            $data = $relation->store($request,$member);
            return $data;
        }

        Log::channel()->notice("User created - id : ".$user->id." and member in family ".$prefix);
    }

}
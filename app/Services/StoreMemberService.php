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
use Illuminate\Support\Facades\Log;
use App\PasswordReset;
use App\Services\StoreRelationAfterMemberCreateService;



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
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'day_of_birth' => $request->day_of_birth
        ]);
        $member->setTable(Auth::User()->prefix.'_members');
        $member->save();

        $user->notify(new UserInvite($user));
        
        Log::channel()->notice("User created - id : ".$user->id." and member in family ".$prefix);
        if($request->partner_id || $request->parent_id){
            $relation = new StoreRelationAfterMemberCreateService();
            $data = $relation->store($request,$member);
            return (['relation' => $data,'member' => $member]);
        }
        return (['relation' => null,'member' => $member]);
    }

}

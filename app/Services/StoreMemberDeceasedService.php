<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Member;
use Carbon\Carbon;
use App\services\StoreRelationAfterMemberCreateService;
class StoreMemberDeceasedService
{
    public function store($request)
    {
        $password = Str::random(10);
        $prefix = Auth::User()->prefix;
        $user = new User([
            'email' => $password.'@nomail.example',
            'password' => bcrypt($password),
            'activation_token' => "",
            'active' => 1,
            'prefix' => $prefix,           
            'type' => User::DEFAULT_TYPE
        ]);
        $user->save();
        $founderUser = Auth::User();
        $member = new Member([
            'user_id' => $user->id,
            'family_id' => $request->family_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'day_of_birth' => $request->day_of_birth,
            'day_of_death' => $request->day_of_death
        ]);
        $member->setTable(Auth::User()->prefix.'_members');
        $member->save();
        Log::channel()->notice("User created - id : ".$user->id." and member in family ".$prefix);
        if($request->partner_id || $request->parent_id){
            $relation = new StoreRelationAfterMemberCreateService();
            $data = $relation->store($request,$member);
            return (['relation' => null,'member' => $member]);
        }
        return (['relation' => null,'member' => $member]);
        
        
    }
}
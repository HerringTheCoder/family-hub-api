<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\Family;
use App\Member;
use App\Notifications\UserInvite;
use App\Http\Requests\StoreMember;
use App\Http\Requests\UpdateMember;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;

class MemberController extends Controller
{

    public function store(StoreMember $request)
    {
        $password = Str::random(10);
        $prefix = Auth::User()->prefix;
        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($password),
            'activation_token' => Str::random(80),
            'prefix' => $prefix
        ]);
        $user->save();

        $founderUser = Auth::User();
        $member = new Member([
            'user_id' => $user->id,
            'family_id' => $founderUser->family->id
        ]);
        $member->setTable(Auth::User()->prefix.'_members');
        $member->save();
       
        $user->notify(new UserInvite($user));
        return response()->json([
            'message' => 'Success'], 201);
    }


    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(20)
             ]
        );
        return response()->json([
            'message' => 'Success, now u can fill your password',
            'token' => $passwordReset->token,
            'email' => $user->email], 201);
    }


    public function update(Request $request)
    {   
        $member = new Member();
          
        $member->setTable(Auth::User()->prefix.'_members');
        $member->where('user_id',Auth::User()->id)
                ->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'day_of_birth' => $request->day_of_birth,
                'day_of_death' => $request->day_of_death]);

        return response()->json([
            'message' => 'Success, data updated!'], 201);
    }

}

<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Member;
use Carbon\Carbon;
use App\PasswordReset;



class ActivateMemberService
{
    public function active($user) 
    {
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
        $data = [
            'message' => 'Success, now u can fill your password',
            'token' => $passwordReset->token,
            'email' => $user->email
        ];

        return $data;
    }

}

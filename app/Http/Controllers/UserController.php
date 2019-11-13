<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\Family;
use App\Notifications\UserInvite;
use App\Http\Requests\StoreUser;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;

class UserController extends Controller
{

    public function store(Request $request)
    {
        $password = Str::random(10);
        $user = new User([
            'email' => $request->email,
            'password' => bcrypt($password),
            'activation_token' => Str::random(80)
        ]);
        $user->save();
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

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function delete(Request $request)
    {
        $this->user->where('id',$request->id)->update(['active' => 0]);

        return response()->json([
            'message' => 'Success, user deactivated!'], 201);
    }

    public function active(Request $request)
    {
        $this->user->where('id',$request->id)->update(['active' => 1]);

        return response()->json([
            'message' => 'Success, user activated!'], 201);
    }
    

}

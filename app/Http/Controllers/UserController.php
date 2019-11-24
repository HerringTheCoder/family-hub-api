<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function delete($id)
    {
        $user = new User();
          
        $user->where('id',Auth::User()->id)
                ->update([
                'active' => 0]);

        return response()->json([
            'message' => 'Success, user deactivated!'], 201);
    }


    public function active($id)
    {
        $user = new User();
          
        $user->where('id',Auth::User()->id)
                ->update([
                'active' => 1]);

        return response()->json([
            'message' => 'Success, user activated!'], 201);
    }
    

}

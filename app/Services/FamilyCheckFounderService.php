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



class FamilyCheckFounderService
{
    public function check($request)
    {
        $member = new Member();
        $member->setTable($request->name.'_members');
        $member = $member->get()->where('user_id',$request->founder_id);
       //dd($member);
        $families = Family::all();
        foreach ($families as $key) {
            if((($key->founder_id == $request->founder_id) && ($key->id != $request->id)) || ($member->isEmpty())){
                return false;
            }
        }
        return true;
    }

}

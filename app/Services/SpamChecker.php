<?php
namespace App\Services;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Family;
use App\Member;



class SpamChecker
{
   

    public function check() : int
    {
        $number = 0;
        $users = User::all();
        foreach ($users as $key) {
            if(!$key->active && $key->activation_token){
                if(strtotime($key->updated_at) < strtotime('7 day ago')){
                    SpamChecker::deleteMember($key->id, $key->prefix);
                    SpamChecker::deleteUser($key->id, $key->prefix);
                    $number++;
                    //dodać sprawdzanie czy jest founderem jak TAK to ma usuwać tabele rodzinne i rodzine
                }
            }
        }

        return $number;

    }

    public function deleteMember($id, $prefix) : void
    {
        $member = new Member();
        $member->setTable($prefix.'_members');
        $member->where('user_id',$id)->delete();
    }

    public function deleteUser($id, $prefix) : void
    {
        $user = new User();
        $user->where('id',$id)->delete();
    }
}

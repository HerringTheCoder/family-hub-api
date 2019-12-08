<?php
namespace App\Services;

use App\Member;
use App\Pivot;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class PivotService
{
    public function store() : void
    {
        $members = new Member();
        $members->setTable(Auth::User()->prefix.'_members');
        $members = $members->where('user_id','!=',Auth::user()->id)->get();

        foreach ($members as $member) {
            $pivot = new Pivot([
                'user_id' => $member->user_id
            ]);
            $pivot->setTable(Auth::User()->prefix.'_pivot');
            $pivot->save();
        }
    }


    public function delete() : void
    {
        $pivot = new Pivot();
        $pivot->setTable(Auth::User()->prefix.'_pivot');
        $pivot->where('user_id',Auth::User()->id)->delete();
    }
}

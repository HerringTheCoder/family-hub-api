<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Member;
use Carbon\Carbon;
use DB;
use App\Notifications\UserInvite;
use App\PasswordReset;



class StoreRelationAfterMemberCreateService
{
    public function store($request, $member)
    {
        if($request->partner_id){
            $isExist = DB::table(Auth::User()->prefix.'_relations')
            ->where('partner_2_id','=', null)
            ->where('partner_1_id','=', $request->partner_id)
            ->first();

            if($isExist){
                DB::table(Auth::User()->prefix.'_relations')
                ->updateOrInsert(
                    ['partner_1_id' => $request->partner_id],
                    ['partner_2_id' => $member->user_id]);
                    $data = "Added to partner";
                    return $data;
            }else{
                $data = "No added to partner";
                return $data;
            }
        }else{
            $isExist = DB::table(Auth::User()->prefix.'_relations')
            ->where('id','=', $request->parent_id)
            ->first();

            if($isExist){
                DB::table(Auth::User()->prefix.'_relations')
                ->insert(['partner_1_id' => $member->user_id, 'parent_id' => $request->parent_id ]);

                $data = "Added to relation";
                return $data;
            }else{
                dd($request->parent_id);
                $data = "No added to relation";
                return $data;
            }

        }
        
    }

}

<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Family;
use App\Member;
use Carbon\Carbon;
use App\Services\TableService;



class SignupActiveService
{
    public function active($user) : void
    {
        $family = new Family([
            'name' => $user->prefix,
            'founder_id' => $user->id
        ]);
        $family->save();

        $service = new TableService();
        $service->addTables($user->prefix);
        
        $member = new Member([
            'user_id' => $user->id,
            'family_id' => $family->id
        ]);
        $member->setTable($user->prefix.'_members');
        $member->save();
        
        Log::channel()->notice("Member in  ".$user->prefix."_members created - id : ".$member->id);
        Log::channel()->notice("Family created - id : ".$family->id);


    }

}

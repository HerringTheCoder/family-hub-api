<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Family;
use App\Http\Requests\NewsRequest;
use App\Services\FamilyCheckFounderService;

class FamilyController extends Controller
{
    public function __construct(Family $family)
    {
        $this->family = $family;
    }

    public function index()
    {
        $family = $this->family->get();
     
        return response()->json([
            'message' => 'Success',
            'data' => $family
        ], 200 ); 
    }

    public function edit(Request $request)
    {
        $family = $this->family->get()->where('id',$request->id);
        if(!$family->isEmpty()){
            return response()->json([
                'message' => 'Success, found data!',
                'data' => $family
            ], 200); 
        } else{
            return response()->json([
                'message' => 'Success but not found data!'
            ], 200); 
        }
    }

    public function update(Request $request,FamilyCheckFounderService $familyService)
    {
        if($familyService->check($request)){
            $this->family->where('id',$request->id)->update(['founder_id' => $request->founder_id]);
    
            return response()->json([
                'message' => 'Success, data updated!'], 200 );
        }else{
            return response()->json([
                'message' => 'This founder ID is already used in other family!'], 401);
        }
    }

    // public function delete(Request $request)
    // {
    //     $this->family->where('id',$request->id)->delete();
    //     return response()->json([
    //         'message' => 'Success, data deleted'], 201);
    // }
}

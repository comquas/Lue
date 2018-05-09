<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function store(Request $request){

        if (! $auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        $user=new User();
       /* $user->name=$request['name'];
        $user->email=$request['email'];
        $user->password=$request['password'];
        $user->position_id=$request['position'];
        $user->join_date=$request['join_date'];
        $user->location=$request['location'];
        $user->mobine=$request['mobile_no'];


        $user->birthday=$request['birthday'];
        $user->salary=$request['salary'];

        $user->no_of_leave=$request['no_of_leave'];
        $user->sick_leave=$request['sick_leave'];
        $user->bank_name=$request['bank_name'];
        $user->bank_account=$request['bank_account'];
        $user->personal_email=$request['personal_email'];
        $user->github=$request['github'];
        $user->twitter=$request['twitter'];
        $user->slack=$request['slack'];*/



    }
}

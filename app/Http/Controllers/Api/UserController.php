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
       $name=$request['name'];
       $email=$request['email'];
       $password=$request['password'];

   }
}

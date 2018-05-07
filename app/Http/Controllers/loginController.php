<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Position;
use App\Supervisor;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;

class loginController extends Controller
{

    public function apiLogin(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['invalid_email_or_password'], 422);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return response()->json(compact('token'));

    }
    public function getUser(){
        $us=User::all();
        return response()->json(['message'=>$us]);
    }
    public function getSup(){
        $sp=Position::all();
        return response()->json(['message'=>$sp]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class loginController extends Controller
{
public  function apiLogin(Request $request)
{
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

}

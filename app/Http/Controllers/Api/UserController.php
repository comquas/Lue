<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getUser(){
        $user=User::all();
        return response()->json(['message'=>$user]);
    }
}

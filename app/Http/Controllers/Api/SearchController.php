<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search($name){
        $user=User::where('name','like',"%{$name}%")->limit(10)->paginate(10);
        return response()->json(['data'=>$user]);

    }
}

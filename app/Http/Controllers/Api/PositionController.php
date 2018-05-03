<?php

namespace App\Http\Controllers\Api;

use App\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    public function getPosition(){
        $po=Position::all();
        return response()->json(['message'=>$po]);
    }
}

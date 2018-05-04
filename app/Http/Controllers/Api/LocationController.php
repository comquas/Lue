<?php

namespace App\Http\Controllers\Api;

use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function getLocation(){
        $lo=Location::all();
        return response()->json(['message'=>$lo]);
    }
}

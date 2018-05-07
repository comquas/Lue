<?php

namespace App\Http\Controllers\Api;

use App\Location;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function getLocation(){
        $lo=Location::all();
        return response()->json($lo);
    }


}

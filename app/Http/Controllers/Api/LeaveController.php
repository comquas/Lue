<?php

namespace App\Http\Controllers\Api;

use App\Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function getLeave(){
        $le=Leave::all();
        return response()->json($le);
    }
}

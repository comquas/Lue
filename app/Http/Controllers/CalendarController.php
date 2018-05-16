<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Leave;

class CalendarController extends Controller
{
     public function calendar(){
         $leave=Leave::with('user')->get();
         $user=User::all();
         return view('calendar.calendar')->with(['users'=>$user])->with(['leaves'=>$leave]);

     }
}

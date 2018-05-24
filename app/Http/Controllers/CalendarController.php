<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Leave;
use Carbon;

class CalendarController extends Controller
{
     public function calendar(){

     	 $start=Carbon\Carbon::now()->startOfMonth()->toDateString();
     	 $end=Carbon\Carbon::now()->endOfMonth()->toDateString();
     
         $leave=Leave::whereBetween('from',[$start,$end])->with('user')->orWhereBetween('to',[$start,$end])->get();
         $user_birthday=User::whereBetween('birthday',[$start,$end])->get();
         $user_anni=User::whereBetween('join_date',[$start,$end])->get();

         return view('calendar.calendar')->with(['users_birth'=>$user_birthday])
         ->with(['leaves'=>$leave])->with(['users_anni'=>$user_anni]);

     }
}

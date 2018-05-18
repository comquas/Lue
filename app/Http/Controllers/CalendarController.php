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
         $leave=Leave::whereBetween('from',[$start,$end])->with('user')->get();
         $user=User::whereBetween('join_date',[$start,$end])->orWhereBetween('birthday',[$start,$end])->get();
         return view('calendar.calendar')->with(['users'=>$user])->with(['leaves'=>$leave]);

     }
}

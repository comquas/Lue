<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Leave;
use Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
     public function calendar(){

     	 $start=Carbon\Carbon::now()->startOfMonth()->toDateString();
     	 $end=Carbon\Carbon::now()->endOfMonth()->toDateString();
     
         $leave=Leave::whereBetween('from',[$start,$end])->with('user')->orWhereBetween('to',[$start,$end])->get();
         $user_birthday=User::whereBetween('birthday',[$start,$end])->get();
         //$user_anni=User::whereBetween('join_date',[$start,$end])->get();
         $user_anni = User::select(DB::raw('id,name,join_date,year(curdate())-year(join_date) as No_Of_Years'))->where(DB::raw('MONTH(join_date)'),'=', DB::raw('MONTH(NOW())'))
                                ->where(DB::raw('DAY(join_date)'),'>=',DB::raw('DAY(NOW())'))
                                ->orderBy('join_date')
                                ->get();
            // foreach($user_anni as $user)
            // {
            // 	var_dump($user->has_anniversary());
            // }
            // die();
         return view('calendar.calendar')->with(['users_birth'=>$user_birthday])
         ->with(['leaves'=>$leave])->with(['users_anni'=>$user_anni]);

     }
}

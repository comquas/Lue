<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Leave;
use Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = Carbon\Carbon::now();
        $users = User::all();
        $isequal = false;
        $birthmonths = array();
        $anniversary = array();

        //Birthday
        foreach($users as $user)
        {
            $birthday = $user->birthday;
            $birthmonth = $birthday[5].$birthday[6];    
            $birthmonth = intval($birthmonth);
            $current_month = intval($date->month);
    
            if($birthmonth === $current_month)
            {
                $isequal = true;
                array_push($birthmonths,$user->id);
            }
            $anniversary[$user->id] = $user->get_anniversary();
            
        }
        
        $birthdays_of_users = User::select('name','birthday')->wherein('id',$birthmonths)->get();
        
        $anniversary_users = array();
        foreach($anniversary as $user_id=>$year)
        {
            if($year != null)
            {
                array_push($anniversary_users,User::whereid($user_id)->first());
            }  
        }
        
        //Today Leave
        $leaves = Leave::where('status',1)->where('from', '<=' ,$date)->where('to', '>=' ,$date)->get();

       
        return view('home',["user" => Auth::user(), "users"=>$users, "birthdays_of_users" => $birthdays_of_users, "current_month"=>$current_month, "leaves"=>$leaves, "is_profile"=>false, "anniversary_users"=>$anniversary_users]);
    }
}

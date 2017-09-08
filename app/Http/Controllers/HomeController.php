<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        

        $isequal = false;
        $birthmonths = array();
        $anniversary = array();

        //Birthday
        $current_month = intval($date->month);


        
        $birthdays_of_users = User::select('id','name','birthday')
                                ->where(DB::raw('MONTH(birthday)'),'=', DB::raw('MONTH(NOW())'))
                                ->where(DB::raw('DAY(birthday)'),'>=',DB::raw('MONTH(NOW())'))
                                ->orderBy('birthday')
                                ->get();
        

    
        //===========
        //Leave Users
        //===========

        $leaves = Leave::where('status',1)
                    ->where(DB::raw('MONTH(`from`)'),'=', DB::raw('MONTH(NOW())'))
                    ->where(DB::raw('DAY(`from`)'),'>=',DB::raw('DAY(NOW())'))
                    ->orderBy('from')
                    ->get(); 


        $anniversary_users = User::select(DB::raw('id,name,join_date,year(curdate())-year(join_date) as No_Of_Years'))->where(DB::raw('MONTH(join_date)'),'=', DB::raw('MONTH(NOW())'))
                                ->where(DB::raw('DAY(join_date)'),'>=',DB::raw('DAY(NOW())'))
                                ->orderBy('join_date')
                                ->get();
       //dd($anniversary_users);
        return view('home',["user" => Auth::user(), "birthdays_of_users" => $birthdays_of_users, "current_month"=>$current_month, "leaves"=>$leaves, "is_profile"=>false, "anniversary_users"=>$anniversary_users]);
    }
}

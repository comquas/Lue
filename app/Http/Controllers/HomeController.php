<?php

namespace App\Http\Controllers;

use App\AnniversaryCalendar;
use App\BirthdayCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Leave;
use Carbon;
use App\LueCalendar;
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
        $helper = new LueCalendar();
        $helper->buildCalendarFolder();
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
        $helper = new LueCalendar();
        $folder_name = $helper->getCalendarFolderHash();
    
        //$app_url = env("CALENDAR_URL");    
        //dd($app_url);
        $calendar_link = "/calendar"."/".$folder_name."/timeOff.ics";
       
       
       $user = Auth::user();
       $is_profile = false;
       
       $calendar_birthday="/calendar"."/".$folder_name."/birthday.ics";
        $calendar_anniversity="/calendar"."/".$folder_name."/anniversary.ics";

        return view('home',compact("user","birthdays_of_users","current_month","leaves","is_profile","anniversary_users","calendar_link","calendar_birthday","calendar_anniversity"));
    }


}

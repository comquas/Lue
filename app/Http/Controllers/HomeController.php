<?php

namespace App\Http\Controllers;

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
        $birth=new BirthdayCalendar();
        $birth->buildCalendarFolder();
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
        $app_url = env("CALENDAR_URL");                           
       $calendar_link = "webcal://".$app_url."/".$folder_name."/timeOff.ics";


       $user = Auth::user();
       $is_profile = false;

        return view('home',compact("user","birthdays_of_users","current_month","leaves","is_profile","anniversary_users","calendar_link"));
    }

   
}

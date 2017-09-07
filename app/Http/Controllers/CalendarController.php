<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Leave;
use App\User;

class CalendarController extends Controller
{
    public function downloadCalendar()
    {
    	$leaves = Leave::where('status',1)
                    ->where(DB::raw('MONTH(`from`)'),'=', DB::raw('MONTH(NOW())'))
                    ->where(DB::raw('DAY(`from`)'),'>=',DB::raw('DAY(NOW())'))
                    ->orderBy('from')
                    ->get(); 

        //$user_id[];
        $leaveUsers = [];
        foreach($leaves as $leave)
        {
        	$leaveType = '';
        	$user = $leave->user()->first();
        	if($leave->type == 1)
    		{
    			$leaveType = "Annual Leave";
    		}
    		else
    		{
    			$leaveType = "Sick Leave";
    		}
    		$info = array('user'=>$user, 'leave'=>$leave, 'leaveType'=>$leaveType);
    		array_push($leaveUsers,$info);
        	//dd($user);
        }
        dd($leaveUsers[0]['user']->name);
    	//$users = User::wherein('id',$user_id)->first();
    	
    	$location = '';
    	
    	
    	$summary = $user->name." : ".$leaveType;
    	$dtstart = $leaves[0]->from;
    	$dtend = $leaves[0]->to;
    	$description = '';
    	$url = 'www.comquas.com';
    	generateICS($location,$description,$dtstart,$dtend,$summary,$url);
    }
}

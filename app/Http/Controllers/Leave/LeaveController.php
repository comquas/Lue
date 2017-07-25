<?php

namespace App\Http\Controllers\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Leave;
class LeaveController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    function index() {
    	$btn_title = "Apply";
    	$route = route('post_timeout');
    	return view('leave/apply',compact('btn_title','route'));
    }

		function list() {
			$user = Auth::user();
			$leaves = Leave::where('status',0)->whereIn('user_id',$user->staff())->paginate(10);

			return view('leave/list',compact('leaves'));

		}
    function store(Request $request) {
			$this->validate($request, [
            'type' => 'required|integer',
            'from_date' => 'required|date',
						'to_date' => 'required|date',
						'no_of_day' => 'required|numeric'
            ]);

			$user = Auth::user();

			$superviosr_id = $user->superviosr_id ;



			$leave = new Leave();
			
			$leave->user_id = $user->id;
			$leave->type = $request->type;
			$leave->no_of_day = $request->no_of_day;
			$leave->from = Carbon::createFromFormat('d-m-Y', $request->from_date,"Asia/Rangoon");;
			$leave->to = Carbon::createFromFormat('d-m-Y', $request->to_date,"Asia/Rangoon");

			if ($superviosr_id == null || $superviosr_id == "") {
				//no need supervisor
				//apprive it
				$leave = $this->approve_it($leave,$user);
				$leave->user->save();
			}
			else {
				$leave->status = 0;
			}


			$leave->save();

			  return redirect()->route('home');

    }


    function approve_it($leave,$user) {
    	$leave->status = 1;
    	if($leave->type == 1) {
    		$leave->user->no_of_leave = $leave->user->no_of_leave - $leave->no_of_day;	
    	}
    	else if ($leave->type == 2) {
    		$leave->user->sick_leave = $leave->user->sick_leave - $leave->no_of_day;		
    	}
    	$leave->approved_by = $user->id;
    	
    	return $leave;

    }

    function approve($id, Request $request) {

    	$user = Auth::user();
    	$leave = Leave::where('status',0)->where('id',$id)->whereIn('user_id',$user->staff())->first();

    	if($leave == null) {
    		return redirect()->route('not_found');
    	}

    	$leave = $this->approve_it($leave,$user);
    	$leave->user->save();
    	$leave->save();
    	return redirect()->route('list_timeoff');


    }
}

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

			$leave = new Leave();
			$leave->approved_by == "";
			$leave->user_id = $user->id;
			$leave->type = $request->type;
			$leave->no_of_day = $request->no_of_day;
			$leave->from = Carbon::createFromFormat('d-m-Y', $request->from_date,"Asia/Rangoon");;
			$leave->to = Carbon::createFromFormat('d-m-Y', $request->to_date,"Asia/Rangoon");
			$leave->status = 0;


			$leave->save();

			  return redirect()->route('home');

    }
}

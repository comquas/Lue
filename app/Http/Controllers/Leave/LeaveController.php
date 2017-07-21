<?php

namespace App\Http\Controllers\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    function store(Request $request) {
    	 $this->validate($request, [
            'type' => 'required|integer',
            'day' => 'required|integer'
            ]);
    }
}



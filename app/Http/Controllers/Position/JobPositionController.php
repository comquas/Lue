<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Position;

class JobPositionController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
 	function index() {
 		$positions = Position::paginate(10);

 		return view('position.list',["positions" => $positions]);
 	}
 	function add() {
 		return view('position.add',["position" => new Position , "route" => route('position_store'),"btn_title" => "Add"]);
 	}

 	function store(Request $request) {

 		$this->validate($request, [
        	'title' => 'required|unique:positions|min:3',
            'level' => 'required|integer'
    	]);

    	$position = new Position;
    	$position->title = $request->title;
        $position->level = $request->level;
    	$position->save();
    	return redirect()->route('position_list');
 	}

    function update(Request $request,$id) {

        $this->validate($request, [
            'title' => 'required|min:3',
            'level' => 'required|integer'
        ]);

        $position = Position::where("id",$id)->first();
        $position->title = $request->title;
        $position->level = $request->level;
        $position->save();
        return redirect()->route('position_list');
    }

    function edit(Request $request,$id) {

        $position = Position::where("id",$id)->first();
        
        $route_data = ["position" => $position,"route" => route('position_update',['id'=> $id]),"btn_title" => "Update"];

        return view('position.add',$route_data);

    }

    function delete(Request $request,$id) {
        $position = Position::where("id",$id)->first();
        $position->delete();
        return redirect()->route('position_list');
    }
}

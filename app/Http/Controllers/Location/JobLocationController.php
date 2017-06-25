<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Location;

class JobLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
 	function index() {
 		$locations = Location::paginate(10);

 		return view('location.list',["locations" => $locations]);
 	}
 	function add() {

 		return view('location.add',["location" => new Location , "route" => route('location_store'),"btn_title" => "Add"]);

 	}

 	function store(Request $request) {

 		$this->validate($request, [
        	'name' => 'required|unique:locations|min:3',
    	]);

    	$location = new Location;
    	$location->name = $request->name;
    	$location->save();
    	return redirect()->route('location_list');
 	}

    function update(Request $request,$id) {

        $this->validate($request, [
            'name' => 'required|min:3',
        ]);

        $location = Location::where("id",$id)->first();
        $location->name = $request->name;
        $location->save();
        return redirect()->route('location_list');
    }

    function edit(Request $request,$id) {

        $location = Location::where("id",$id)->first();
        
        $route_data = ["location" => $location,"route" => route('location_update',['id'=> $id]),"btn_title" => "Update"];

        return view('location.add',$route_data);

    }

    function delete(Request $request,$id) {
        $location = Location::where("id",$id)->first();
        $location->delete();
        return redirect()->route('location_list');
    }
}

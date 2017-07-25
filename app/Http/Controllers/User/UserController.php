<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Position;
use Carbon\Carbon;
use App\Location;
use App\User;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function edit_profile() {
    	$user = Auth::user();
    	$positions = Position::all();
        $locations = Location::all();
        
        $param = compact('user','positions','locations');
        $param['btn_title'] = 'Update';
        $param['route'] = route('profile_update');
        
        return view('user/edit_profile', $param);
    }

    function edit($id) {
        $user = User::where("id",$id)->first();
        if($user == null) {
            return redirect()->route('user_list');
        }
        $positions = Position::all();
        $locations = Location::all();
        
        
        return view('user/edit_profile',["user" => $user,"positions" => $positions,"locations" => $locations,"btn_title" => "Update", "route" => route('user_update',['id'=>$user->id])]);
    }
    function add() {
        $positions = Position::all();
        $locations = Location::all();
        return view('/user/edit_profile',["positions" => $positions,"locations" => $locations,"btn_title" => "Add","route" => route('store_user')]);
    }

    function null_empty($string) {
        if ($string == null) {
            return "";
        }
        return $string;
    }

    function store(Request $request) {
        $this->validate($request, [
            'avatar' => 'required|image|mimes:jpeg,bmp,png|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:d-m-Y',
            'birthday' => 'nullable|date|date_format:d-m-Y',
            'password' => 'required|string|confirmed',
            'no_of_leave' => 'required|integer',
            'sick_leave' => 'required|integer',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable|string'
            ]);

        $this->update("",$request);
        return redirect()->route('user_list');
    }

    function showList() {
        $user = User::paginate(10);

        return view('/user/user_list',["users" => $user,"current_user"=> Auth::user()]);
    }

    
    function user_update(Request $request, $id) {

        $this->validate($request, [
            'avatar' => 'nullable|image|mimes:jpeg,bmp,png|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:d-m-Y',
            'birthday' => 'date|date_format:d-m-Y',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
            'no_of_leave' => 'nullable|integer',
            'sick_leave' => 'nullable|integer',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable|string'
            ]);

        $user = User::where("id",$id)->first();
        if($user == null) {
            return redirect()->route('user_list');
        }

        $this->update($user->id,$request);

        return redirect()->route('user_list');

    }

    function profile($id, Request $request) {


        $user = User::where('id',$id)->first();

        if ($user == null) {
            return redirect()->route('not_found');
        }

        
        return view('home',compact('user'));

    }
    function update_profile(Request $request) {



    	$this->validate($request, [
            'avatar' => 'nullable|image|mimes:jpeg,bmp,png|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:d-m-Y',
            'birthday' => 'date|date_format:d-m-Y',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
            'no_of_leave' => 'nullable|integer',
            'sick_leave' => 'nullable|integer',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable|string'
            ]);

        $user = Auth::user();

        $this->update($user->id,$request);

        return redirect()->route('home');
    }

    private function update($id = "", Request $request) {

        if ($id != "") {
            $user = User::where("id",$id)->first();
        }
        else {
            $user = new User;
        }

        if ($request->avatar != null) {
            //move to public folder
            $photoName = time().'.'.$request->avatar->getClientOriginalExtension();
            $request->avatar->move(public_path('avatars'), $photoName);

            if ($user->avatar != null) {
                //delete old
                File::delete(public_path('avatars')."/".$user->avatar);
            }
            $user->avatar = $photoName;
        }


        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = $request->mobile_no;
        $user->position_id = $request->position;
        $user->location_id = $request->location;
        $user->join_date = Carbon::createFromFormat('d-m-Y', $request->join_date,"Asia/Rangoon");
        $user->birthday = Carbon::createFromFormat('d-m-Y', $request->birthday,"Asia/Rangoon");


        
        $user->supervisor_id = $request->supervisor;    
        
        
        

        if(trim($request->password) != "") {
            $user->password = bcrypt($request->password);
        }
        $user->no_of_leave = $request->no_of_leave;
        $user->sick_leave = $request->sick_leave;
        $user->bank_name = $this->null_empty($request->bank_name);
        $user->bank_account = $this->null_empty($request->bank_account);
        $user->personal_email = $this->null_empty($request->personal_email);
        $user->github = $this->null_empty($request->github);
        $user->twitter = $this->null_empty($request->twitter);

        $user->save();

    }

    function search(Request $request) {

        if (!isset($request->name)) {
            return;
        }

        
        //$user = User::paginate(10);
        $users = User::where('name','like',$request->name."%")->limit(10)->paginate(10);
        $current_user = Auth::user();
        $q = $request->name;

        return view('/user/user_list',compact("users" ,"current_user","q"));

    }

    function ajax_search(Request $request) {

        if (!isset($request->q)) {
            return;
        }

        $user = Auth::user();
        $result= User::select('id','name as text')->where('name','like',$request->q."%")->limit(10)->get();

        echo $result;exit;

        
    }
}

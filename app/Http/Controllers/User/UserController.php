<?php

namespace App\Http\Controllers\User;

use App\BirthdayCalendar;
use function GuzzleHttp\Psr7\_parse_request_uri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Position;
use Carbon\Carbon;
use App\Location;
use App\User;
use Illuminate\Support\Facades\DB;
use File;
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
    function delete(User $user){


        $img_path=public_path("avatars/{$user->avatar}");
        //return $img_path;
        if(File::exists($img_path)){
            unlink($img_path);
        }
        //file::delete($img_path);
        $user->delete();
        return back();
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
        $user = User::orderBy('name', 'asc')->paginate(10);    
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

        
        return view('home',['is_profile'=>true, 'user'=>$user]);

    }
    function update_profile(Request $request) {


        $user = Auth::user();

        if($user->is_admin()) {
            $this->validate($request, [
            'avatar' => 'nullable|image|mimes:jpeg,bmp,png|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:d-m-Y',
            'birthday' => 'date|date_format:d-m-Y',
            'salary' => 'required|integer',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
            'no_of_leave' => 'nullable|integer',
            'sick_leave' => 'nullable|integer',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable|string',
            'slack' => 'nullable|string'
            ]);
        }
        else {
            $this->validate($request, [
            'avatar' => 'nullable|image|mimes:jpeg,bmp,png|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:d-m-Y',
            'birthday' => 'date|date_format:d-m-Y',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable|string',
            'slack' => 'nullable|string'
            ]);
        }

    	

        

        
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

            $avatar = $request->avatar;
            $ext = $request->avatar->getClientOriginalExtension();
            
            //change photoname with original extension
            $photoName = time().'.'.$ext;
            //dd(public_path('avatars'));
            //move to  folder
            $request->avatar->move(public_path('avatars'), $photoName);

            //store the moved photo path
            $filename = public_path('avatars')."/$photoName";

            //crop image
            //=====================================
                $im = null;
                if( $ext == "png") {
                    $im = imagecreatefrompng($filename);
                }
                else if ($ext == "jpg" ) {
                    $im = imagecreatefromjpeg($filename);
                }

                if ($im != null) {
                    //size according to minimum size of photo
                    $size = min(imagesx($im), imagesy($im));
                    
                    //crop image
                    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
                    

                    if ($im2 !== FALSE) {
                        if( $ext == "png") {
                            imagepng($im2, public_path('avatars')."/$photoName");
                        }
                        else {
                            imagejpeg($im2, public_path('avatars')."/$photoName");
                        }
                    }
                }
                
            //end crop image
            //========================================

            if ($user->avatar != null) {
                File::delete(public_path('avatars')."/".$user->avatar);
            }
            $user->avatar = $photoName;
        }

        //dd(Carbon::createFromFormat('d-m-Y', $request->join_date,"Asia/Rangoon"));
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = $request->mobile_no;
        $user->position_id = $request->position;
        $user->location_id = $request->location;
        $user->join_date = Carbon::createFromFormat('d-m-Y', $request->join_date,"Asia/Rangoon");
        $user->birthday = Carbon::createFromFormat('d-m-Y', $request->birthday,"Asia/Rangoon");
        
        
        

        if(trim($request->password) != "") {
            $user->password = bcrypt($request->password);
        }
        
        $user->bank_name = $this->null_empty($request->bank_name);
        $user->bank_account = $this->null_empty($request->bank_account);

        //only allow for Admin Level
        if(Auth::user()->is_admin()) {
            $salary = $this->null_empty($request->salary);
            $user->salary = $salary;    

            $user->no_of_leave = $request->no_of_leave;
            $user->sick_leave = $request->sick_leave;
            //$user->urgent_leave=$request->urgent_leave;
            $user->supervisor_id = $request->supervisor;     
        }
        

        $user->personal_email = $this->null_empty($request->personal_email);
        $user->github = $this->null_empty($request->github);
        $user->twitter = $this->null_empty($request->twitter);
        $user->slack = $this->null_empty($request->slack);
        $user->save();
        ///////write calendar from here

        $helper=new BirthdayCalendar();
        $helper->writeCalendar($user);

    }    function search(Request $request) {

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
    public function resetLeave($id){
        $user=User::where('id',$id)->first();
        $user->no_of_leave=$user->no_of_leave;
        $user->sick_leave=$user->sick_leave;
        $user->save();
        return redirect()->back();
    }
    public function BAT(){
        $user=User::all();
        return view('home')->with(['user'=>$user]);
    }
}

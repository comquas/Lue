<?php

namespace App\Http\Controllers\Api;

use App\Location;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class LocationController extends Controller
{
    public function store(Request $request){

        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
          $validator = Validator::make($request->all(), [
         'name' => 'required|unique:locations|min:3',
         
      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
    
          
            $location=new Location();
            $location->name=$request['name'];
            $location->save();
         return response()->json(['success'=>true,'message'=>'Created Successfully','data'=>$location],200);
        }
        else{
            return response()->json(['message'=>'Permission defined'],401);

        }
    }
        public function delete($id){
            if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['User Not Found'], 422);

            }
            if($auth_user->position->level===1){
                $location=Location::find($id);
                if($location){
                    $location->delete();
                    return response()->json(['success'=>true,'message'=>"delete Successfully"],200);
                }
                else{
                    return response()->json(['success'=>false,'message'=>'Not found'],422);

                }

            }
            else{
                return response()->json(['message'=>'Permission defined'],401);
            }

    }
    public function update(Request $request,$id){
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){

        
          $validator = Validator::make($request->all(), [
          'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
          }
    

            $location=Location::find($id);
    
            if($location){
                $location->name=$request->name ? $request->name : $location->name;
            
                $location->update();
                return response()->json(['success'=>true,'message'=>'Update Successfully'],200);
            }
            else{
                return response()->json(['success'=>false,'message'=>'Not found'],422);
            }
        }
        else{
            return response()->json(['message'=>'Permission defined'],401);

        }

    }
    public function index(){

        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
            $location=Location::paginate(10);
            return response()->json(['success'=>true,'data'=>$location],200);

        }
        else{
            return response()->json(['message'=>'Permission defined'],401);

        }

    }
  public function show($id)
  {
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if ($auth_user->position->level===1) {
        $dt=new Carbon();
       

       dd($dt->year);
       //
          $location = Location::where('id', $id)->first();
          if($location){
              return response()->json(['success'=>true,'data'=>$location],200);

          }else{
              return response()->json(['message'=>'Not Found'],422);
          }

      }
  else{
      return response()->json(['message' => 'Permission defined'], 401);
  }
  }
  

  public function updateProfile(Request $request){
  
      $user=Auth::user();
      $data = [
           'avatar' => 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:Y-m-d',
            'birthday' => 'date|date_format:Y-m-d',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'password' => 'nullable|string|confirmed',
            'no_of_leave' => 'nullable|integer',
            'sick_leave' => 'nullable|integer',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable|string',
            'slack' => 'nullable|string'
      ];
      if($user->position->level===1){
      $data=['salary'=>'required'];
 
      }
      


      $validator = Validator::make($request->all(), $data);
          
               if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
     

      
     
            $user = User::find($user->id);
            if ($user) {
                $user->name = $request->name ? $request->name: $user->name;
                $user->email = $request->email ? $request->email : $user->email;
                $user->password = $request->password ? bcrypt($request->password) : $user->password;
                $user->position_id=$request->position ? $request->position : $user->position_id;
                $user->join_date = $request->join_date ? $request->join_date : $user->join_date;



                if ($request->bank_name or $request->bank_name==null) {
                    $user->bank_name=$request->bank_name;
            
                }else{
                    $user->bank_name=$user->bank_name;
                }
                 if ($request->bank_account or $request->bank_account==null) {
                    $user->bank_account=$request->bank_account;
                
                }else{
                    $user->bank_account=$user->bank_account;
                }
                $user->salary = $request->salary ? $request->salary : $user->salary;
                $user->no_of_leave = $request->no_of_leave ? $request->no_of_leave : $user->no_of_leave;
                $user->sick_leave = $request->sick_leave ? $request->sick_leave : $user->sick_leave;
                $user->mobile_no = $request->mobile_no ? $request->mobile_no : $user->mobile_no;
                $user->personal_email = $request->personal_email ? $request->personal_email : $user->personal_email;
                $user->birthday = $request->birthday ? $request->birthday : $user->birthday; 
                if ($request->github or $request->github==null) {
                    $user->github=$request->github;
                    # code...
                }else{
                    $user->github=$user->github;
                }
                 if ($request->twitter or $request->twitter==null) {
                    $user->twitter=$request->twitter;
                    # code...
                }else{
                    $user->twitter=$user->twitter;
                }
                if ($request->slack or $request->slack==null) {
                    $user->slack=$request->slack;
                    # code...
                }else{
                    $user->slack=$user->slack;
                }

                $user->location_id=$request->location ? $request->location : $user->location;
                $user->supervisor_id=$request->supervisor ? $request->supervisor : $user->supervisor;

                if($request->avatar)
                {
                    if($user->avatar)
                    {
                        unlink(public_path('/avatars/').$user->avatar);
                    }

                    $user->avatar=$this->getBase64Image($request->avatar);
                }

                $user->update();
                $user->avatar=asset('avatars/'.$user->avatar);
                return response()->json(['data'=>$user],200);


            } else {
                return response()->json(['message' => 'Not Found'], 402);
            }
    
        }

 protected function getBase64Image($image)
    {
        $photo = base64_decode($image);
        $photo_name=time().str_random(3). '.jpg';
        file_put_contents(public_path('/avatars/').$photo_name, $photo);

        return $photo_name;
    }
}
<?php

namespace App\Http\Controllers\Api;
use App\Location;
use App\Position;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use File;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rule;
use Carbon\Carbon;


class UserController extends Controller
{
    # code...


    public function store (Request $request){
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);
        }
        if($auth_user->position->level===1){
         $validator = Validator::make($request->all(), [
         'avatar' => 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'nullable|string',
            'position' => 'required|integer',
            'location' => 'required|integer',
            'join_date' => 'date|date_format:Y-m-d',
            'birthday' => 'nullable|date|date_format:Y-m-d',
            'password' => 'required|string|confirmed',
            'no_of_leave' => 'required|integer',
            'sick_leave' => 'required|integer',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'personal_email' => 'nullable|string|email|max:255',
            'github' => 'nullable|string',
            'twitter' => 'nullable',
            'slack'=>'nullable',

      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
            $photo=base64_decode($request['avatar']);
            $photo_name=time().str_random(3). '.jpg';
            $user=new User();
            $user->name=$request['name'];
            $user->email=$request['email'];
            $user->password=bcrypt($request['password']);
            $user->avatar=$photo_name;
            $user->position_id=$request['position'];
            $user->join_date=$request['join_date'];
            $user->bank_name=$request['bank_name'];
            $user->bank_account=$request['bank_account'];
            $user->salary=$request['salary'];
            $user->no_of_leave=$request['no_of_leave'];
            $user->sick_leave=$request['sick_leave'];
            $user->mobile_no=$request['mobile_no'];
            $user->personal_email=$request['personal_email'];
            $user->birthday=$request['birthday'];
            $user->github=$request['github'];
            $user->twitter=$request['twitter'];
            $user->slack=$request['slack'];
            $user->location_id=$request['location'];
            $user->supervisor_id=$request['supervisor'];
            $user->save();
           file_put_contents(public_path('avatars/').$photo_name, $photo);
            return response()->json(['success'=>'true','data'=>$user],200);

        }
        else{
            return response()->json(['message'=>'Permission defined'],401);
        }

    }

    public function profile(Request $request,$id){

        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
            $user=User::find($id);
            if($user){
                  return [

                    'name'=>$user->name,
                    'email'=>$user->email,
                    'avatar'=>url("/")."/avatars/".$user->avatar,
                    'mobile_no'=>$user->mobile_no,
                    'Address'=>$user->location->name,
                    'join_date'=>$user->join_date,
                    'birthday'=>$user->birthday,
                    'position'=>$user->position->title,
                    'no_of_leave'=>$user->no_of_leave,
                    'sick_leave'=>$user->sick_leave,
                    'time'=> Carbon::parse($user->join_date)->diff(Carbon::now())->format('%y years, %m months and %d days')
                ];


            }
            else{
                return response()->json(['message'=>'Not Found'],422);

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
            'avatar' => 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'nullable|string',
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
            'twitter' => 'nullable|string'
         

      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
            $user = User::find($id);
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
                
                return response()->json(['data'=>$user],200);


            } else {
                return response()->json(['message' => 'Not Found'], 402);
            }
    
        }
         
    }

    protected function getBase64Image($image)
    {
        $photo = base64_decode($image);
        $photo_name=time().str_random(3). '.jpg';
        file_put_contents(public_path('/avatars/').$photo_name, $photo);

        return $photo_name;
    }


    public function delete(Request $request,$id){
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
            $user=User::find($id);
           if($user){
               $img_path=public_path("avatars/{$user->avatar}");
               if(File::exists($img_path)){
                   unlink($img_path);
               }
               $user->delete();
               return response()->json(['success'=>true,'data'=>'Delete Successfully'],200);




               return response()->json(['success'=>true],200);
           }
           else{
               return response()->json(['success'=>false,'message'=>'Not Found'],422);
           }
        }
        else{
            return response()->json(['message'=>'Permission defined'],422);
        }
    }
    public function search()
    {
        $name = request('keyword');

        $user=User::where('name','like',"%{$name}%")->limit(10)->paginate(10);

        return response()->json(['data'=>$user]);

    }
    public function showList(Request $request ){
       if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

           return response()->json(['User Not Found'], 422);

       }
       if($auth_user->position->level ===1){
          $users = User::orderBy('name','asc')->paginate(10);
          return UserResource::collection($users);
       }
       return response()->json(['message'=>'Permission defined'],401);

}
    public function resetLeave($id){


        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
            $user=User::where('id',$id)->first();
     
            if($user){
               $user->no_of_leave=0;
                $user->sick_leave=0;
                $user->save();
                return response()->json(['success'=>true,'data'=>$user],200);
            }
            else{
                return response()->json(['message'=>'Not Found'],422);
            }

        }
        else{
            return response()->json(['message'=>'Permission defined'],401);
        }

    }
    
    public function userUpdate(Request $request)
    {

         if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        $user=User::find($auth_user->id);
         $user->name = $request->name ? $request->name: $user->name;
                $user->email = $request->email ? $request->email : $user->email;
                $user->password = $request->password ? bcrypt($request->password) : $user->password;
                $user->position_id=$request->position ? $request->position : $user->position_id;
                $user->join_date = $request->join_date ? $request->join_date : $user->join_date;
                $user->bank_name = $request->bank_name ? $request->bank_name : $user->bank_name;
                $user->bank_account = $request->bank_account ? $request->bank_account : $user->bank_account;
                $user->salary = $request->salary ? $request->salary : $user->salary;
                $user->no_of_leave = $request->no_of_leave ? $request->no_of_leave : $user->no_of_leave;
                $user->sick_leave = $request->sick_leave ? $request->sick_leave : $user->sick_leave;
                $user->mobile_no = $request->mobile_no ? $request->mobile_no : $user->mobile_no;
               
                //dd($request->personal_email);
              
                 $user->personal_email = $request->personal_email  ? $request->personal_email : $user->personal_email=null;
                $user->birthday = $request->birthday ? $request->birthday : $user->birthday;
                $user->github = $request->github ? $request->github : $user->
                github;
                $user->twitter = $request->twitter ? $request->twitter : $user->twitter;
                $user->slack = $request->slack ? $request->slack : $user->slack;
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
                return response()->json(['data'=>$user],200);



       

    }

    
}

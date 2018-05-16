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


class UserController extends Controller
{
    public function create(Request $request){
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);
        }
        if($auth_user->position->level===1){
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

    public function showUserProfileById(Request $request,$id){

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
                    'join_date'=>$user->join_date,
                    'birthday'=>$user->birthday,
                    'no_of_leave'=>$user->no_of_leave,
                    'sick_leave'=>$user->sick_leave,
                    'location'=>$user->location->name




                ];



            }
        }
        else{
            return response()->json(['message'=>'Not Found'],422);
        }


        return response()->json(['message'=>'Permission defined'], 401);

    }
    public function show()
    {
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if ($auth_user->position->level === 1) {
            $user = User::paginate(10);
            return response()->json(['success' => true, 'data' => $user], 200);

        } else {
            return response()->json(['message' => 'Permission defined'], 401);


        }

    }
    public function update(Request $request,$id){
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        //if($id==$auth_user->id) {


            $user = User::find($id);
            if ($user) {
                $user->name = $request->name ? $request->name: $user->name;
                $user->email = $request->email ? $request->email : $user->email;
                $user->password = $request->password ? bcrypt($request->password) : $user->password;
                $user->join_date = $request->join_date ? $request->join_date : $user->join_date;
                $user->bank_name = $request->bank_name ? $request->bank_name : $user->bank_name;
                $user->bank_account = $request->bank_account ? $request->bank_account : $user->bank_account;
                $user->salary = $request->salary ? $request->salary : $user->salary;
                $user->no_of_leave = $request->no_of_leave ? $request->no_of_leave : $user->no_of_leave;
                $user->sick_leave = $request->sick_leave ? $request->sick_leave : $user->sick_leave;
                $user->mobile_no = $request->mobile_no ? $request->mobile_no : $user->mobile_no;
                $user->personal_email = $request->personal_email ? $request->personal_email : $user->personal_email;
                $user->birthday = $request->birthday ? $request->birthday : $user->birthday;
                $user->github = $request->github ? $request->github : $user->github;
                $user->twitter = $request->twitter ? $request->twitter : $user->twitter;
                $user->slack = $request->slack ? $request->slack : $user->slack;

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
       // }
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

}

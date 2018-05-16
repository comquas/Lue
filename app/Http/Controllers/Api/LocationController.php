<?php

namespace App\Http\Controllers\Api;

use App\Location;
use App\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LocationController extends Controller
{
    public function create(Request $request){

        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
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
    public function show(){

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
  public function showById($id)
  {
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if ($auth_user->position->level) {
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

}

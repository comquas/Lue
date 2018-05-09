<?php

namespace App\Http\Controllers\Api;

use App\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class PositionController extends Controller
{
  public function create(Request $request){
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }

      if($auth_user->position->level===1){
          $title=$request['title'];
          $level=$request['level'];
          $position=new Position();
          $position->title=$title;
          $position->level=$level;
          $position->save();
          return response()->json(['success'=>true,'message'=>'Created Successfully'],200);


      }else{
          return  response()->json(['message'=>'Permission defined'],401);
      }
  }
  public  function delete($id){
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if($auth_user->position->level===1){
          $position=Position::find($id);
          if($position){
              $position->delete();
              return response()->json(['success'=>true,'message'=>'delete Successfully'],200);
          }
          else{
              return response()->json(['success'=>false,'message'=>'Not Found'],402);

          }
      }else{
          return response()->json(['message'=>'Permission defined'],401);
      }
  }
  public function update(Request $request,$id){
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if($auth_user->position->level===1){
          $title=$request['title'];
          $level=$request['level'];
          $position=Position::find($id);
          if($position){
              $position->title=$title;
              $position->level=$level;
              $position->update();
              return response()->json(['success'=>true,'message'=>'updated Successfully'],200);
          }
          else{
              return response()->json(['success'=>false,'message'=>'Not Found'],402);
          }

      }else{
          return response()->json(['message'=>'Permission defined'],401);
      }

  }
  public function show(){
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if($auth_user->position->level===1){
          $position=Position::paginate(2);
          return response()->json(['success'=>true,'data'=>$position],200);

      }else{
          return response()->json(['message'=>'Permission defined'],401);
      }


  }
  public function showById($id){
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if($auth_user->position->level===1){
          $position=Position::where('id',$id)->first();
          if($position){
              return response()->json(['success'=>true,'data'=>$position],200);
          }
          else{
              return response()->json(['message'=>'Not Found'],402);
          }
      }
      else{
          return response()->json(['message'=>'Permission defined'],401);
      }
  }
}

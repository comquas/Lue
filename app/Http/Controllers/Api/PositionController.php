<?php

namespace App\Http\Controllers\Api;

use App\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\User;

class PositionController extends Controller
{
  public function store(Request $request){

     
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }

      if($auth_user->position->level===1){

         $validator = Validator::make($request->all(), [
          'title' => 'required|unique:positions|min:3',
          'level' => 'required|integer',
      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
    
          $position=new Position();
          $position->title=$request['title'];
          $position->level=$request['level'];
          $position->save();
          return response()->json(['success'=>true,'message'=>'Created Successfully','data'=>$position],200);


      }else{
          return  response()->json(['message'=>'Permission defined'],401);
      }
  }
public function delete(Request $request,$id){
    if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

        return response()->json(['User Not Found'], 422);

    }
    if($auth_user->position->level===1) {
        $position = Position::find($id);
        if ($position) {
            $position->delete();
            return response()->json(['success'=>true,'data'=>'Delete Successfully'],200);

        }else{
            return response()->json(['success'=>false,'message'=>'Not Found'],422);
        }
    }

    else{
       return response()->json(['message'=>'Permission defined',401]);
    }
}
  public function update(Request $request,$id){
    
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if($auth_user->position->level===1){

        $validator = Validator::make($request->all(), [
           'title' => 'required|min:3',
            'level' => 'required|integer'
      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
    
          $position=Position::find($id);
          if($position){
              $position->title=$request->title ? $request->title : $position->title;
              $position->level=$request->level ? $request->level : $position->level;
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
  public function index(){
      if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

          return response()->json(['User Not Found'], 422);

      }
      if($auth_user->position->level===1){
          $position=Position::paginate(10);
          return response()->json(['success'=>true,'data'=>$position],200);

      }else{
          return response()->json(['message'=>'Permission defined'],401);
      }


  }
  public function show($id){
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

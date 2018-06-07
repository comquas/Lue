<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Leave extends Model
{

    function user() {
    	return $this->hasOne('App\User','id','user_id');
    }

    public function get_from_date() {
        return Carbon::createFromFormat('Y-m-d',$this->from,"Asia/Rangoon")->format('d-m-Y');
    }

    public function get_to_date() {
        return Carbon::createFromFormat('Y-m-d',$this->to,"Asia/Rangoon")->format('d-m-Y');
    }

    public static function approveUser($id,$user){

   		return $leave = Leave::join('users','leaves.user_id','users.id')
        ->where('leaves.id', $id)
        ->where('users.supervisor_id',$user->id)
        ->where('leaves.status',0)
        ->select('leaves.*')
        ->first();

   }

   public static function rejectUser($leave_id,$user){
   	return $leave = Leave::where('status', 0)->where('id', $leave_id)->whereIn('user_id', $user->staff())->first();
   }
   public static function editTimeOffUser($id,$user){
    return 
        $leave = Leave::where('status', 0)->where('id', $id)->whereIn('user_id', $user->staff())
            ->first();

   }

   public static function applyRequestList($user){
    return
      $leaves = Leave::join('users','leaves.user_id','users.id')
        ->where('users.supervisor_id',$user->id)
        ->where('leaves.status',0)
        ->orderBy('leaves.created_at', 'desc')
        ->select('leaves.*')
        ->paginate(10);
   }

   public static function applyTimeOffList($user){
    return
      $leaves = Leave::join('users','leaves.user_id','users.id')
        ->where('users.supervisor_id',$user->id)
        ->orderBy('leaves.created_at', 'desc')
        ->select('leaves.*')
        ->paginate(10);
   }

    
}

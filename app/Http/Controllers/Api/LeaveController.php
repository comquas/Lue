<?php

namespace App\Http\Controllers\Api;

use App\Leave;
use App\LueCalendar;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\LeaveResource;
use JWTAuth;
use League\OAuth2\Server\Grant\AuthCodeGrant;

class LeaveController extends Controller
{
  
    public function show()
    {
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if ($auth_user->position->level === 1) {
            $user = Auth::User();
            $leaves = Leave::join('users', 'leaves.user_id', 'users.id')
                ->where('users.supervisor_id', $user->id)
                ->where('leaves.status', 0)
                ->orderBy('leaves.created_at', 'desc')
                ->select('leaves.*')
                ->paginate(10);
            return response()->json(['success' => true, 'data' => $leaves], 200);

        } else {
            return response()->json(['message' => 'Permission defined'], 401);
        }
    }

    public function update(Request $request, $id="")
    {
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        //return $id;
        if($auth_user->position->level==1){
          $validator = Validator::make($request->all(), [
          'type' => 'required',
          'from_date'=>'required',
          "to_date"=>"required",
          "no_of_day"=>'required',
          "reason"=>'required',
        
      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
      $leave=Leave::find($id);
      if($leave){
        $leave->type = $request->type;
        $leave->no_of_day = $request->no_of_day;
        $leave->reason=$request->reason;
        $leave->from = Carbon::createFromFormat('Y-m-d', $request->from_date, "Asia/Rangoon");;
        $leave->to = Carbon::createFromFormat('Y-m-d', $request->to_date, "Asia/Rangoon");
        $leave->update();
        return response()->json(['success'=>true,'data'=>$leave]);


      }
      else{
        return response()->json(['message'=>'Not Found'],422);

      }
      
        }
        else{
          return response()->json(['message'=>"Permission defined"],401);

        }
    }

    public function store(Request $request, $id=""){

        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
          $validator = Validator::make($request->all(), [
          'type' => 'required',
          'from_date'=>'required',
          "to_date"=>"required",
          "no_of_day"=>'required',
          "reason"=>'required',
        
      ]);

      if ($validator->fails()) {
          return response(['message'=> $validator->errors()->first()], 422);
      }
       $user = Auth::user();

        if ($id == '' || $id == null)
        {

            $leave = new Leave();
            $supervisor_id = $user->supervisor_id;
            $leave->user_id = $user->id;
        }
        else
        {
            $leave = Leave::where('status', 0)->where('id', $id)->whereIn('user_id', $user->staff())
                ->first();
            $supervisor_id = $leave
                ->user->supervisor_id;
            $leave->user_id = $leave
                ->user->id;
        }
         $leave->type = $request->type;
        $leave->no_of_day = $request->no_of_day;
        $leave->reason=$request->reason;
        $leave->from = Carbon::createFromFormat('Y-m-d', $request->from_date, "Asia/Rangoon");;
        $leave->to = Carbon::createFromFormat('Y-m-d', $request->to_date, "Asia/Rangoon");
         if ($supervisor_id == null || $supervisor_id == "")
        {
            //no need supervisor
            //apprive it
            $leave = $this->approve_it($leave, $user);

            $leave
                ->user
                ->save();
            $leave->user->save();
        }
        else
        {
            $leave->status = 0;

        }

        $leave->save();

  if ($supervisor_id != null || $supervisor_id != "")
        {
            if ($id == '' || $id == null)
            {
                

               // $this->sendApplyMail($user, $leave);
                //send Slack
                $leave_type = "";
                if ($leave->type == 1) $leave_type = "Annual";
                else $leave_type = "Sick";

                $text = $user->name . " will take leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") for " . $leave_type . " leave";

                $supervisor = User::whereid($supervisor_id)->first();

                
                $this->sendSlack($user, $supervisor, $text);

            }
        }
        else {
            //write to calendar
            $helper = new LueCalendar();
            $helper->writeCalendar($leave);
        }

        //send Mail
        

        if ($id == '' || $id == null)
        {
          return response()->json(['success'=>true,'data'=>$leave],200);
        }
        
     

        }

      
    }
    public function sendSlack($send_user, $receive_user, $text)
    {

        $url = env('SLACK_HOOK', '');
        if ($url == "")
        {
            return false;
        }

        $json = ["channel" => $receive_user->slack, "username" => $send_user->name, "text" => $text];
        $json = ["channel" => "@".$receive_user->slack, "username" => $send_user->name, "text" => $text];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'payload=' . json_encode($json));
        $server_output = curl_exec($ch);
        curl_close($ch);
    }




    function approve_it($leave, $user)
    {

        $leave->status = 1;
        if ($leave->type == 1)
        {
            $leave
                ->user->no_of_leave = $leave
                    ->user->no_of_leave - $leave->no_of_day;
        }
        else if ($leave->type == 2)
        {
            $leave
                ->user->sick_leave = $leave
                    ->user->sick_leave - $leave->no_of_day;
        }
        $leave->approved_by = $user->id;

        return $leave;

    }

   
   public function reject(Request $request){


       if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

           return response()->json(['User Not Found'], 422);

       }
       if ($auth_user->position->level === 1) {

           $leave_id = $request->get('leave_id');
          //dd($request->leave_id);
           $remark = $request->get('remark');


           $leave = Leave::where('status', 0)->where('id', $leave_id)->whereIn('user_id', $auth_user->staff())
               ->first();
           if($leave ==""){
               return response()->json(['message'=>'Not Found'],422);
           }else{
               $leave->status = 2;
               $leave->remark = $remark;
               $leave->approved_by = $auth_user->id;
               // dd($request->all());
               $leave->save();
               return response()->json(['success'=>true,'data'=>$leave],200);
           }
           //var_dump($auth_user->staff());

           //send Mail
          $this->sendRejectMail($user, $leave);
           //send Slack
           $leave_type = "";
           if ($leave->type == 1) $leave_type = "Annual";
           else $leave_type = "Sick";
           $text = $text = "You are not allowed the " . $leave_type . "leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;
           $text = $text = "You are not allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;
           $text = "You are not allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;

           $this->sendSlack($user, $leave->user, $text);

           //return redirect()->route('list_timeoff');
       }

       function sendRejectMail($user, $leave)
       {
           $fromEmail = $user->email;

           config(['mail.from.address' => $fromEmail]);

           $email = $leave
               ->user->email;

           $send = Mail::to($email)->send(new RejectLeave($leave, $user));
       }


   }
   public function approve(Request $request,$id)
   {
       if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

           return response()->json(['User Not Found'], 422);

       }
       if ($auth_user->position->level === 1) {

           //$user = Auth::user();

           $leaveId = [];
           foreach ($auth_user->staff() as $leaveUser) {
               $leaveId[] = $leaveUser->id;
           }


           $leave = Leave::join('users', 'leaves.user_id', 'users.id')
               ->where('leaves.id', $id)
               ->where('users.supervisor_id', $auth_user->id)
               ->where('leaves.status', 0)
               ->select('leaves.*')
               ->first();


           if ($leave == null) {
               //return redirect()->route('not_found');
               return response()->json(['message'=>'Not Found'],422);
           }

           $leave = $this->approve_it($leave, $auth_user);
           $leave->user->save();
           $leave->save();
           return response()->json(['success'=>true,'data'=>$leave],200);
           $helper = new LueCalendar();

           $helper->writeCalendar($leave);

          // $this->sendApproveMail($auth_user, $leave);
           //send Slack
           $leave_type = "";
           if ($leave->type == 1) {
               $leave_type = "Annual";
           } else {
               $leave_type = "Sick";
           }
           $text = "You are allowed the " . $leave_type . "leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ")";
           $text = "You are allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ")";
           $this->sendSlack($user, $leave->user, $text);

           //return redirect()->route('list_timeoff');
           //return response()->json(['success'=>true,'data'=>$leave])
       }


       else{
               return response()->json(['message' => 'Permission defined'], 401);
           }

       }
    function sendApproveMail($user, $leave)
    {
        $fromEmail = $user->email;

        config(['mail.from.address' => $fromEmail]);

        $email = $leave
            ->user->email;

        Mail::to($email)->send(new ApproveLeave($leave, $user));
    }


    
  
  public function adminTimeOffList(Request $request){
   if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
         $user = Auth::user();
       
        $id=[];
        foreach($user->staff() as $leaveUser)
        {
            $id[] = $leaveUser->id;
        }
        //DB::enableQueryLog();
        $decision = true;
        $leaves = Leave::join('users','leaves.user_id','users.id')
        ->where('users.supervisor_id',$user->id)
        ->where('leaves.status',0)
        ->orderBy('leaves.created_at', 'desc')
        ->select('leaves.*')
        ->paginate(10);
         return LeaveResource::collection($leaves);

        }else{
           return response()->json(['message'=>'Permission defined'],401);
        }
    }
   public function adminDecidedTimeOffList(Request $request){
    if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if($auth_user->position->level===1){
          $user=Auth::user();
           $leaves = Leave::join('users','leaves.user_id','users.id')
        ->where('users.supervisor_id',$user->id)
        ->orderBy('leaves.created_at', 'desc')
        ->select('leaves.*')
        ->paginate(10);
      return LeaveResource::collection($leaves);

        }
        else{
          return response()->json(['message'=>'Permission defined'],401);
        }
   }
   
}
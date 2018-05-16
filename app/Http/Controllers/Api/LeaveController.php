<?php

namespace App\Http\Controllers\Api;

use App\Leave;
use App\LueCalendar;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public function update(Request $request, $id)
    {
        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }
        if ($auth_user->position->level === 1) {
            $leave = Leave::find($id);
            if ($leave) {
                $leave->type = $request->type ? $request->type : $leave->type;
                $leave->no_of_day = $request->no_of_day ? $request->no_of_day : $leave->no_of_day;
                $leave->reason = $request->reason ? $request->reason : $leave->reason;
                $leave->from = $request->from_date ? $request->from_date : $leave->from;
                $leave->to = $request->to_date ? $request->to_date : $leave->to;
                $leave->update();
                return response()->json(['success' => true, 'data' => $leave], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Not Found'], 422);
            }

        } else {
            return response()->json(['message' => 'Permission defined'], 401);
        }

    }

    public function create(Request $request, $id=""){

        if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

            return response()->json(['User Not Found'], 422);

        }

        $user = Auth::user();

        if ($id == '' || $id == null)
        {

            $leave = new Leave();
            $supervisor_id = $user->supervisor_id;
            $leave->user_id = $user->id;
            //dd($user->id);
        }
        else
        {
            $leave = Leave::where('status', 0)->where('id', $id)->whereIn('user_id', $user->staff())
                ->first();
            $supervisor_id = $leave
                ->user->supervisor_id;
            $leave->user_id = $leave
                ->user->id;;
        }

        $leave->type = $request->type;
        $leave->no_of_day = $request->no_of_day;
        $leave->reason=$request->reason;
        $leave->from = Carbon::createFromFormat('d-m-Y', $request->from_date, "Asia/Rangoon");;
        $leave->to = Carbon::createFromFormat('d-m-Y', $request->to_date, "Asia/Rangoon");

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

        //send mail
        if ($supervisor_id != null || $supervisor_id != "")
        {
            if ($id == '' || $id == null)
            {


                $this->sendApplyMail($user, $leave);
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
            return redirect()->route('home');
        }
        else
        {
            return redirect()
                ->route('list_timeoff');
        }

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



    public function time_off_list(){
       if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

           return response()->json(['User Not Found'], 422);

       }
       if($auth_user->position->level===1){


       }

   }
   public function reject(Request $request)
   {
       if (!$auth_user = JWTAuth::parseToken()->authenticate()) {

           return response()->json(['User Not Found'], 422);

       }
       if ($auth_user->position->level === 1) {

           $leave_id = $request->get('leave_id');
           $remark = $request->get('remark');


           $leave = Leave::where('status', 0)->where('id', $leave_id)->whereIn('user_id', $auth_user->staff())
               ->first();
           if($leave==""){
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
          // $this->sendRejectMail($user, $leave);
           //send Slack
           $leave_type = "";
           if ($leave->type == 1) $leave_type = "Annual";
           else $leave_type = "Sick";
           $text = $text = "You are not allowed the " . $leave_type . "leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;
           $text = $text = "You are not allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;
           $text = "You are not allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;

           //$this->sendSlack($user, $leave->user, $text);

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

       //dd("hello");
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
          // $this->sendSlack($user, $leave->user, $text);

           //return redirect()->route('list_timeoff');
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
   }

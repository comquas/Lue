<?php
namespace App\Http\Controllers\Leave;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Leave;
use App\User;
use App\Mail\ApproveLeave;
use App\Mail\RejectLeave;
use App\Mail\ApplyLeave;
use App\LueCalendar;
use DB;
use File;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    function index()
    {
        $btn_title = "Apply";
        $route = route('post_timeout');
        return view('leave/apply', compact('btn_title', 'route'));
    }

    
    function timeOffList()
    {
        $user = Auth::user();
       
        $id=[];
        foreach($user->staff() as $leaveUser)
        {
            $id[] = $leaveUser->id;
        }
        DB::enableQueryLog();
        $decision = true;
       
        $leaves = Leave::applyRequestList($user);
        //dd($leaves);
        return view('leave/list', compact('leaves','decision'));
    }

    function decidedList()
    {
        $user = Auth::user();

        $leaves = Leave::applyTimeOffList($user);
        //dd($leaves);   
        return view('leave/list', compact('leaves'));
    }

    function store(Request $request, $id='')
    {
    
      $this->validate($request, ['type' => 'required|integer', 'from_date' => 'required|date', 'to_date' => 'required|date', 'no_of_day' => 'required|numeric','reason' => 'required']);

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
            //dd($helper);
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

    function sendApplyMail($user, $leave)
    {
        $fromEmail = $user->email;
        config(['mail.from.address' => $fromEmail]);
        $supervisor_id = $leave
            ->user->supervisor_id;
        $supervisor = User::whereid($supervisor_id)->first();
        Mail::to($supervisor->email)
            ->send(new ApplyLeave($leave, $user, $supervisor));
    }

    public function sendSlack($send_user, $receive_user, $attachments)
    {
        if(!$receive_user)
        {
            return false;
        }

        $url = env('SLACK_HOOK', '');
        if ($url == "")
        {
            return false;
        }

        // $json = ["channel" => $receive_user->slack, "username" => $send_user->name, "text" => $text];
        $json = ["channel" => "@".$receive_user->slack, "username" => $send_user->name, "attachments" => $attachments];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'payload=' . json_encode($json));
        $server_output = curl_exec($ch);
        curl_close($ch);
    }
     public function approveSendSlack($send_user, $receive_user, $attachments)
    {
        if(!$receive_user->slack)
        {
            return false;
        }
        //dd($receive_user->slack);

        $url = env('SLACK_HOOK', '');
        if ($url == "")
        {
            return false;
        }

        // $json = ["channel" => "@{$receive_user->slack}", "username" => $send_user->name, "text" => 'hi'];
        $json = ["channel" => "@".$receive_user->slack, "username" => $send_user->name, "attachments" => $attachments];
        
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
        }else if($leave->type == 3)
        {
            $leave
                ->user->urgent_leave = $leave
                ->user->urgent_leave - $leave->no_of_day;
        }
        $leave->approved_by = $user->id;

        return $leave;

    }

    function approve($id, Request $request)
    {
        
        $user = Auth::user();
        
        $leaveId=[];
        foreach($user->staff() as $leaveUser)
        {
            $leaveId[] = $leaveUser->id;
        }


        $leave = Leave::approveUser($id,$user);

        //dd($leave);

        if ($leave == null)
        {
            return redirect()->route('not_found');
        }

        $leave = $this->approve_it($leave, $user);
        $leave->user->save();
        $leave->save();
        $helper = new LueCalendar();

        $helper->writeCalendar($leave);

        $this->sendApproveMail($user, $leave);
        //send Slack
        $leave_type = "";
        if ($leave->type == 1)
        {
            $leave_type = "Annual";
        }
        else if ($leave->type == 2)
        {
            $leave_type = "Sick";
        }
        else
        {
            $leave_type = "Urgent";
        }
        //$text = "You are allowed the " . $leave_type . "leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ")";
        //$text = "You are allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ")";
        $attachments=[
            ["title" => $leave->user->name . " apply leave for " . $leave->no_of_day
        . "days", 
             "fields" => [
            ["title" => "From" , "value" => $leave->from, "short" => true],
            ["title" => "To", "value" => $leave->to, "short" => true],
            ["title" => "No of Days","value"=> $leave->no_of_day, "short" => true],
            ["title" => "Comment" , "value" => $leave->reason, "short"=>true]]]];
        //dd(json_encode($attachments));

        $this->approveSendSlack($user, $leave->user, $attachments);

        return redirect()->route('list_timeoff');
    }

    function sendApproveMail($user, $leave)
    {
        $fromEmail = $user->email;

        config(['mail.from.address' => $fromEmail]);

        $email = $leave
            ->user->email;
        

        Mail::to($email)->send(new ApproveLeave($leave, $user));
    }

    function edit($id, Request $request)
    {
        $user = Auth::user();
        $leave = Leave::editTimeOffUser($id,$user);
           // dd($leave);
        $from = new Carbon($leave->from);
        $to = new Carbon($leave->to);
        $leave_days = $from->diff($to)->days;
        $showOnce = true;
        $btn_title = "Apply";
        $route = route('update_timeout', ['id' => $id]);
        return view('leave/apply', compact('btn_title', 'route', 'leave', 'leave_days', 'showOnce'));
    }

    function reject(Request $request)
    {
        $user = Auth::user();
        $leave_id = $request->get('leave_id');
        $remark = $request->get('remark');

        $leave = Leave::rejectUser($leave_id,$user);
        //dd($leave);

        $leave->status = 2;
        $leave->remark = $remark;
        $leave->approved_by = $user->id;
        $leave->save();
        //send Mail
        $this->sendRejectMail($user, $leave);
        //send Slack
        $leave_type = "";
        if ($leave->type == 1) $leave_type = "Annual";
        else $leave_type = "Sick";

        $attachments=[
            ["title" => "Reject Leave", 
             "fields" => [
            ["title" => "From" , "value" => $leave->from, "short" => true],
            ["title" => "To", "value" => $leave->to, "short" => true],
            ["title" => "No of Days","value"=> $leave->no_of_day, "short" => true],
            ["title" => "Comment" , "value" => $leave->remark, "short"=>true]]]];

        // $text = "You are not allowed the " . $leave_type . " leave from " . $leave->from . " to " . $leave->to . "(" . $leave->no_of_day . ") because " . $leave->remark;

        $this->sendSlack($user, $leave->user, $attachments);

        return redirect()->route('list_timeoff');
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


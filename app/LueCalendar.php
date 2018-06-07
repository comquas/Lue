<?php
namespace App;

use Carbon;
use Illuminate\Support\Facades\File;

class LueCalendar
{
	function getCalendarFolderHash($filename="timeOff") {
        $app_key = env('APP_KEY');
        $folder_name = md5($filename.$app_key);
        return $folder_name;
    }

    function buildCalendarFolder() {

    	$filename="timeOff";

    	$folder_name = $this->getCalendarFolderHash($filename);
        $path = public_path() ."/calendar/$folder_name";
        if(!File::exists($path))
        {
            File::makeDirectory($path,0775,true);

            $file = $path ."/$filename.ics";
            $leaves = Leave::where('status',1)
                    ->orderBy('from')
                    ->get();
            $leaveUsers = $this->generateAllLeaveUsersInfo($leaves);
            $contents = response()->view('webcal.leave', ['leaveUsers' => $leaveUsers,
            ],200);
            $bytes_written = File::put($file, $contents->getContent());
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }

        }


    }

    public function generateAllLeaveUsersInfo($leaves)
    {
        $leaveUsers = [];
        foreach($leaves as $leave)
        {
            $leaveType = '';
            $user = $leave->user()->first();
            if($leave->type == 1)
            {
                $leaveType = "Annual Leave";
            }
            else if($leave->type == 2)
            {
                $leaveType = "Sick Leave";
            }else
            {
                $leaveType = "Urgent Leave";
            }
            $info = array('id'=>$user->id,'name'=>$user->name, 'from'=>$leave->from,'to'=>Carbon::parse($leave->to)->addDay(), 'leaveType'=>$leaveType, 'timestamp'=>$leave->created_at);
            array_push($leaveUsers,$info);
            
        }
        return $leaveUsers;
    }

    public function writeCalendar($leaves)
    {
        
        $filename = 'timeOff';
        $helper = new LueCalendar();
        $folder_name = $helper->getCalendarFolderHash();
        $path = public_path() ."/calendar/$folder_name";
        if(!File::exists($path)) 
        {
            File::makeDirectory($path);
        }
        
        
        $file = $path ."/$filename.ics";

        if(file_exists( $file ))
        {
            
            $lines = file($file);
            $last = sizeof($lines)-1;
            unset($lines[$last]);

            // write the new data to the file 
            $fp = fopen($file, 'w'); 

            fwrite($fp, implode('', $lines));
            fclose($fp);

            $leaveUser = $this->generateLeaveUserInfo($leaves);
            $content = "
BEGIN:VEVENT
METHOD:PUBLISH
CREATED:".date_format($leaveUser['timestamp'],'Ymd').'T'.date_format($leaveUser['timestamp'],'His').'Z'."
TRANSP:OPAQUE
X-APPLE-TRAVEL-ADVISORY-BEHAVIOR:AUTOMATIC
SUMMARY:".$leaveUser['name']." : ".$leaveUser['leaveType']."
DTSTART;VALUE=DATE:".date_format(date_create($leaveUser['from']),'Ymd').'T'.date_format(date_create($leaveUser['from']),'His').'Z'."
DTEND;VALUE=DATE:".date_format(date_create($leaveUser['to']),'Ymd').'T'.date_format(date_create($leaveUser['to']),'His').'Z'."
DTSTART;TZID=Asia/Rangoon:".date_format(date_create(Carbon\Carbon::now()),'Ymd').'T'.date_format(date_create(Carbon\Carbon::now()),'His')."
SEQUENCE:0
END:VEVENT
END:VCALENDAR";

          
            $bytesWritten = File::append($file, $content);
            if ($bytesWritten === false)
            {
                die("Couldn't write to the file.");
            }
        }
        
    }

    

    public function generateLeaveUserInfo($leave)
    {
        

            $user = $leave->user()->first();
            if($leave->type == 1)
            {
                $leaveType = "Annual Leave";
            }
            else if($leave->type == 2)
            {
                $leaveType = "Sick Leave";
            }
            else
            {
                $leaveType = "Urgent Leave";
            }
            $info = array('id'=>$user->id,'name'=>$user->name, 'from'=>$leave->from,'to'=>$leave->to, 'leaveType'=>$leaveType, 'timestamp'=>$leave->created_at);
        return $info;
    }
}
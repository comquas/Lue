<?php
namespace App;

use Carbon\Carbon;
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
LOCATION:
DESCRIPTION: 
DTSTART;VALUE=DATE:".str_replace("-", "", $leaveUser['from'])."
DTEND;VALUE=DATE:".str_replace("-", "", $leaveUser['to'])."
SUMMARY: ".$leaveUser['name']." : ".$leaveUser['leaveType']."
URL;VALUE=URI:www.comquas.com
DTSTAMP: ".$leaveUser['timestamp']."
UID: 0".$leaveUser['id']."
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
            $info = array('id'=>$user->id,'name'=>$user->name, 'from'=>$leave->from,'to'=>Carbon::parse($leave->to)->addDay(), 'leaveType'=>$leaveType, 'timestamp'=>$leave->created_at);
        return $info;
    }
}
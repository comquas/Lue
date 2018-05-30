<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\AnniversaryCalendar;
use App\BirthdayCalendar;

class LueCalendar
{
    protected $anniversary_calendar;
    protected $birthday_calendar;

    public function __construct()
    {
        $this->anniversary_calendar = new AnniversaryCalendar;
        $this->birthday_calendar = new BirthdayCalendar;
    }

	function getCalendarFolderHash($filename="timeOff") {
        $app_key = env('APP_KEY');
        $folder_name = md5($filename.$app_key);
        return $folder_name;
    }

    function buildCalendarFolder() {

    	$filename="timeOff";
        $filename1 = 'anniversary';
        $filename2 = 'birthday';

    	$folder_name = $this->getCalendarFolderHash($filename);
        $path = public_path() ."/calendar/$folder_name";

        if(!File::exists($path))
        {
            File::makeDirectory($path,0775,true);

            $file  = "$path/$filename.ics";
            $file1 = "$path/$filename1.ics";
            $file2 = "$path/$filename2.ics";

            //Leave User
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

            $user=User::all();

            //Birthday User
            $birthday_user = $this->generateAllBirthdayUsersInfo($user);
            $contents = response()->view('webcal.birthday', ['users' => $birthday_user,
            ],200);
            $bytes_written = File::put($file2, $contents->getContent());
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }

            //Anniversary User
            $anniversary_user = $this->generateAllAnniversaryUsersInfo($user);
            $contents = response()->view('webcal.anniversary', ['users' => $anniversary_user,
            ],200);
            $bytes_written = File::put($file1, $contents->getContent());
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
            $info = array('id'=>$user->id,'name'=>$user->name, 'from'=>$leave->from,'to'=>$leave->to, 'leaveType'=>$leaveType, 'timestamp'=>$leave->created_at);
            array_push($leaveUsers,$info);
            
        }
        return $leaveUsers;
    }

    public function generateAllAnniversaryUsersInfo($user)
    {
        $users= [];
        foreach($user as $usr)
        {

            $info = array('id'=>$usr->id,'name'=>$usr->name, 'join_date'=>$usr->join_date,'created_at'=>$usr->created_at);
            array_push($users,$info);

        }

        return $users;
    }

    public function generateAllBirthdayUsersInfo($user)
    {
        $users= [];
        foreach($user as $usr)
        {

            $info = array('id'=>$usr->id,'name'=>$usr->name, 'birthday'=>$usr->birthday,'created_at'=>$usr->created_at);
            array_push($users,$info);

        }

        return $users;
    }

    public function writeCalendar($leaves)
    {
        
        $filename = 'timeOff';
        $filename1 = 'anniversary';
        $filename2 = 'birthday';

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
DTSTART;TZID=Asia/Rangoon:".date_format(date_create($leaveUser['from']),'Ymd').'T'.date_format(date_create($leaveUser['from']),'His')."
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
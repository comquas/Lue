<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use App\LueCalendar;

class AnniversaryCalendar extends Model
{

    function buildCalendarFolder() {

        $filename="anniversary";
        $helper=new LueCalendar();
        $folder_name = $helper->getCalendarFolderHash("timeOff");
        $path = public_path() ."/calendar/$folder_name";
        $file = $path ."/$filename.ics";
        $user=User::all();
        $users = $this->generateAllLeaveUsersInfo($user);
        $contents = response()->view('webcal.anniversary', ['users' => $users,
        ],200);
        $bytes_written = File::put($file, $contents->getContent());
        if ($bytes_written === false)
        {
            die("Error writing to file");
        }


    }

    public function generateAllLeaveUsersInfo($user)
    {
        $users= [];
        foreach($user as $usr)
        {

            $info = array('id'=>$usr->id,'name'=>$usr->name, 'join_date'=>$usr->join_date);
            array_push($users,$info);

        }

        return $users;
    }

    public function writeCalendar($user)
    {
        $filename = 'anniversary';
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

            $anniversary = $this->generateBirthdayUserInfo($user);

            $content = "
BEGIN:VEVENT
LOCATION:
DESCRIPTION: 
SUMMARY: ".$anniversary['name']."
URL;VALUE=URI:www.comquas.com
DTSTAMP: ".$anniversary['join_date']."
UID: 0".$anniversary['id']."
END:VEVENT
END:VCALENDAR";

            $bytesWritten = File::append($file, $content);
            if ($bytesWritten === false)
            {
                die("Couldn't write to the file.");
            }
        }

    }
    public function generateBirthdayUserInfo($user)
    {


        $usr = $user->first();
        $info = array('id'=>$usr->id,'name'=>$usr->name, 'join_date'=>$usr->join_date);
        return $info;
    }

}

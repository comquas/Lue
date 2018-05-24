BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN

@foreach($leaveUsers as $key => $leaveUser)

BEGIN:VEVENT
METHOD:PUBLISH
CREATED:{{date_format(new DateTime($leaveUser['timestamp']),'Ymd')}}T{{date_format(new DateTime($leaveUser['timestamp']),'His')}}Z
TRANSP:OPAQUE
X-APPLE-TRAVEL-ADVISORY-BEHAVIOR:AUTOMATIC
SUMMARY:{{$leaveUser['name']}} : {{$leaveUser['leaveType']}}
DTSTART;VALUE=DATE:{{date_format(new DateTime($leaveUser['from']),'Ymd')}}T{{date_format(new DateTime($leaveUser['from']),'His')}}Z
DTEND;VALUE=DATE:{{date_format(new DateTime($leaveUser['to']),'Ymd')}}T{{date_format(new DateTime($leaveUser['to']),'His')}}Z
DTSTART;TZID=Asia/Rangoon:{{date_format(Carbon\Carbon::now(),'Ymd')}}T{{date_format(Carbon\Carbon::now(),'His')}}
SEQUENCE:0
END:VEVENT

@endforeach

END:VCALENDAR

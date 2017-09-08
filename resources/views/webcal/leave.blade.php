BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN

@foreach($leaveUsers as $key => $leaveUser)
BEGIN:VEVENT
LOCATION:
DESCRIPTION:
DTSTART;VALUE=DATE:{{str_replace("-", "", $leaveUser['from'])}}
DTEND;VALUE=DATE:{{str_replace("-", "", $leaveUser['to'])}}
SUMMARY:{{$leaveUser['name']}} : {{$leaveUser['leaveType']}}
URL;VALUE=URI:www.comquas.com
DTSTAMP:{{$leaveUser['timestamp']}}
UID:0{{$leaveUser['id']}}
END:VEVENT
@endforeach

END:VCALENDAR

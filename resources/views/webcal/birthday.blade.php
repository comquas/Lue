BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN

@foreach($users as $key => $user)


BEGIN:VEVENT
METHOD:PUBLISH
CREATED:{{date_format(new DateTime($user['created_at']),'Ymd')}}T{{date_format(new DateTime($user['created_at']),'His')}}Z
TRANSP:OPAQUE
X-APPLE-TRAVEL-ADVISORY-BEHAVIOR:AUTOMATIC
SUMMARY:{{$user['name']}} Birthday
DTSTART;TZID=Asia/Rangoon:{{date_format(new DateTime($user['birthday']),'Ymd')}}T000000
RRULE:FREQ=YEARLY
SEQUENCE:0
END:VEVENT

@endforeach

END:VCALENDAR

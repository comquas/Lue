BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
CALSCALE:GREGORIAN

@foreach($users as $key => $user)

    BEGIN:VEVENT
    LOCATION:
    DESCRIPTION:
    SUMMARY:{{$user['name']}}
    URL;VALUE=URI:www.comquas.com
    DTSTAMP:{{$user['join_date']}}
    RRULE:FREQ=YEARLY
    UID:0{{$user['id']}}
    END:VEVENT

@endforeach

END:VCALENDAR

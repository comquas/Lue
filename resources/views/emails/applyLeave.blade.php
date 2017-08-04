@component('mail::message')
# Applied Leave

Applied To<br>
{{ $supervisor->name }}

@component('mail::table')
| Title       | Description       |
| ------------- |:-------------:| 
| Name       | {{$leave->user->name}}        |
| Leave Type      | @if($leave->type==1) {{"Annual Leave"}} @else {{"Sick Leave"}} @endif      | 
| No of leave      | {{$leave->no_of_day}} | 
| From      | {{$leave->from}}      | 
| To      | {{$leave->to}} |
@endcomponent

With Regards<br>
{{ $leave->user->name }}

@endcomponent

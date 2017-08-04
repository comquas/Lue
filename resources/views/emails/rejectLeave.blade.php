@component('mail::message')
# Rejected Leave

@component('mail::table')
| Title       | Description       |
| ------------- |:-------------:| 
| Name       | {{$leave->user->name}}        |
| Leave Type      | @if($leave->type==1) {{"Annual Leave"}} @else {{"Sick Leave"}} @endif      | 
| No of leave      | {{$leave->no_of_day}} | 
| From      | {{$leave->from}}      | 
| To      | {{$leave->to}} |
| Remark      | {{$leave->remark}} |
@endcomponent

Rejected By<br>
{{ $user->name }}
@endcomponent

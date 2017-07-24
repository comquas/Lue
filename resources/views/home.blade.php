@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row topbar-info">

        <div class="bar"></div>
        <div class="col-md-2 avatar">
            <img src="{{ url('avatars')}}/{{ $user->avatar }}" class="rounded" style="width:140px;">
        </div>
        <div class="col-md-10 name-bar">

            <div class="user-name">
                <b>{{ $user->name }}</b>
                <div> {{ $user->position->title }}</div>
                <div> {{ $user->get_long_time() }}</div>
            </div>
        </div>
    </div>
    
    <div class="row info-bar-detail">
        <div class="col-md-2">
            <ul class="user-info-data">
        <li><b>Leave : </b>{{ $user->no_of_leave }} days left</li>
        <li><b>Sick leave : </b>{{ $user->sick_leave }} days left</li>
        </ul>
        </div>
        <div class="col-md-10">
            <ul class='user-info-detail'>
            <li><label>Email</label><span>{{ $user->email }}</span></li>
            <li><label>Mobile</label><span>{{ $user->mobile_no }}</span></li>
            <li><label>Address</label><span>{{ $user->location->name }}</span></li>
            <li><label>Join Date</label><span>{{ $user->get_join_date() }}</span></li>
            <li><label>Birthday</label><span>{{ $user->get_birthday() }} ({{ $user->age() }})</span></li>
            </ul>
        </div>
    </div>
</div>
@endsection

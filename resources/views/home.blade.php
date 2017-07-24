@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">


        <div class="col-md-2">
            <img src="{{ url('avatars')}}/{{ $user->avatar }}" class="rounded" style="width:140px;">
        </div>
        <div class="col-md-10">
            <b>{{ $user->name }}</b>
            <div> {{ $user->position->title }}</div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <ul class="user-info-data">
        <li>3 years in office</li>
        <li>5 day left for leave</li>
        <li>2 day left for sick leave</li>
        </ul>
        </div>
        <div class="col-md-10">
            <ul class='user-info-detail'>
            <li><label>Email</label><span>{{ $user->email }}</span></li>
            <li><label>Mobile</label><span>{{ $user->mobile }}</span></li>
            <li><label>Address</label><span>{{ $user->location->name }}</span></li>
            <li><label>Join Date</label><span>{{ $user->join_date }}</span></li>
            <li><label>Birthday</label><span>{{ $user->birthday }}</span></li>
            </ul>
        </div>
    </div>
</div>
@endsection

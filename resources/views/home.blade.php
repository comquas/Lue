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
            <ul>
        <li>3 years in office</li>
        <li>5 day left for leave</li>
        <li>2 day left for sick leave</li>
        </ul>
        </div>
        <div class="col-md-10">
            {{ $user->email }}

            {{ $user->mobile }}
            {{ $user->location->name }}
            {{ $user->join_date }}
            {{ $user->birthday }}
        </div>
    </div>
</div>
@endsection

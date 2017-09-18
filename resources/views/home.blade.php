@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row topbar-info">

        <div class="bar"></div>
        <div class="col-md-2 avatar">
            <img src="{{ url('avatars') }}/{{ $user->avatar }}" class="avatar-img rounded">
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

            @if (Auth::user()->is_admin() || $is_profile == false)
                <li><b>Leave : </b>{{ $user->no_of_leave }} days left</li>
                <li><b>Sick leave : </b>{{ $user->sick_leave }} days left</li>
            @endif
        @isset($user->supervisor)
        <li><b>Manager : </b><a href="{{ route('user_profile',["id" => $user->supervisor->id])}}">{{ $user->supervisor->name }}</a></li>
        @endisset

        @if (Auth::user()->is_admin() || $is_profile == false)
                <li><a class="btn btn-primary edit-home" href="{{ route('edit_user',["id"=> $user->id]) }}">Edit</a></li>
        @endif
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
    @if($is_profile==false)
    <div class="row info-card">
    <!-- Brithday List -->
    <div class="col-4">
        <div class="card">
            <div class="card-header">
        <h6>🎂 Birthday</h6>
            </div>
            <div class="card-block">
            <ul class="user-info-data">
                @if(count($birthdays_of_users)!=0)
                @foreach($birthdays_of_users as $birthday_user)
                <li><a href="{{ route('user_profile',["id" => $birthday_user->id])}}">{{$birthday_user->name}}</a> , {{date('d F',strtotime($birthday_user->birthday))}}</li>
                @endforeach
                @else
                    <li>There is no brithday</li>
                @endif
            </ul>
    
    </div>
    </div>
    </div>
    <!-- End Brithday List -->

    <!-- Anniversary List -->
    <div class="col-4">
    <div class="card">
        <div class="card-header">
        <h6>💪 Anniversary</h6>
        </div>
        <div class="card-block">
        <ul class="user-info-data">
        @if(count($anniversary_users))
        @foreach($anniversary_users as $anniversary_user)
            
            
                <li><a href="{{ route('user_profile',["id" => $anniversary_user->id])}}">{{$anniversary_user->name}}</a> , {{$anniversary_user->No_Of_Years}} 

                @if($anniversary_user->No_Of_Years > 1) 
                    Years 
                @else 
                    Year 
                @endif

                at {{date('d/m',strtotime($anniversary_user->join_date))}} 

                </li>
            
           
        @endforeach
        @else
            <li>There is no anniversary</li>
        @endif
        </ul>
    </div>
    </div>
    </div>
    <!-- End Anniversary List -->

    <!-- Anniversary List -->
    <!-- Leave List -->
    <div class="col-4">
    <div class="card">
        <div class="card-header">
        <h6>👨‍ Time-Off</h6>
        <a class="calendar-link" href="{{$calendar_link}}">🗓</a>
        </div>
        <div class="card-block">
            
        
        <ul class="user-info-data">
       @if(count($leaves)!=0)
       @foreach($leaves as $leave)

                <li>

                <a href="{{ route('user_profile',["id" => $leave->user->id])}}">{{$leave->user->name}} </a>
                @if($leave->type == 2)
                    😷
                @endif
                <br/>
                <div class='small'>

                from {{date('d/m',strtotime($leave->from))}} 
                to {{date('d/m',strtotime($leave->to))}} 
                , {{$leave->no_of_day}} 

                @if($leave->no_of_day > 1) 
                    days 
                @else 
                    day 
                @endif

                </div></li> 
                  
       @endforeach
       @else
            <li>Wow.. nobody take leave yet 👍</li> 
       @endif
        </ul>
    </div>
    </div>
    </div>
    <!-- End Anniversary List -->
    <!-- End Leave List -->

    </div>
    @endif
</div>
@endsection

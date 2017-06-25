@extends('layouts.app')


@section('header')
<script type="text/javascript" src={{ asset('js/plugins/bootstrap-datepicker.js') }}></script>
<script type="text/javascript" src={{ asset('js/plugins/moment.min.js') }}></script>

@endsection

@section('content')
<div class="container">
    <form action="{{ route('profile_update') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">


            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Profile Image
                    </div>
                    <div class="card-block">

                        <div class="admin-profile-image">
                            <img src="{{ url('avatars')}}/{{ $user->avatar }}" class="rounded" style="width:140px;">
                        </div>
                        <div class="form-group">
                            <label class="custom-file">

                                <input type="file" name="avatar" id="avatar" class="custom-file-input">
                                <span class="custom-file-control"></span>
                            </label>
                            
                        </div>
                        @if ($errors->has('avatar'))
                        <span class="help-block">
                            <strong>{{ $errors->first('avatar') }}</strong>
                        </span>
                        @endif


                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        Profile
                    </div>
                    <div class="card-block">






                        @component('control.textbox')
                        @slot('title','Name')
                        @slot('name','name')
                        @slot('placeholder','Enter Your Name')
                        @slot('value',$user->name)
                        @endcomponent

                        @component('control.textbox')
                        @slot('title','Email')
                        @slot('name','email')
                        @slot('placeholder','Enter Your Office Email')
                        @slot('value',$user->email)
                        @endcomponent

                        @component('control.textbox')
                        @slot('title','Mobile')
                        @slot('name','mobile_no')
                        @slot('placeholder','Enter Your mobile number')
                        @slot('value',$user->mobile_no)
                        @endcomponent

                        @component('control.picker')
                        @slot('title','Office Location')
                        @slot('name','location')
                        @slot('selected_id',isset($user->location->id) ? $user->location->id : '')
                        @slot('objects',$locations)
                        @slot('type',"name")
                        @endcomponent

                        @component('control.picker')
                        @slot('title','Position')
                        @slot('name','position')
                        @slot('selected_id',isset($user->position->id) ? $user->position->id : '')
                        @slot('objects',$positions)
                        @slot('type',"title")
                        @endcomponent

                        



                        @component('control.datepicker')
                        @slot('title','Join')
                        @slot('id','join-date')
                        @slot('name','join_date')
                        @slot('value',$user->get_join_date())
                        @endcomponent

                        @component('control.datepicker')
                        @slot('title','Birthday')
                        @slot('id','birthday')
                        @slot('name','birthday')
                        @slot('value',$user->get_birthday())
                        @endcomponent

                    </div>
                </div>
            </div>

            <div class="col-md-6">    
                <div class="card">
                    <div class="card-header">
                        Time-Off
                    </div>
                    <div class="card-block">

                        @component('control.textbox')
                        @slot('title','Leave')
                        @slot('name','no_of_leave')
                        @slot('placeholder','No. Of Leave')
                        @slot('value',$user->no_of_leave)
                        @endcomponent
                    </div>
                </div>


                <div class="card">
                   <div class="card-header">
                       Bank Info
                   </div>
                   <div class="card-block">



                    @component('control.textbox')
                    @slot('title','Bank Name')
                    @slot('name','bank_name')
                    @slot('placeholder','Bank Name')
                    @slot('value',$user->bank_name)
                    @endcomponent

                    @component('control.textbox')
                    @slot('title','Bank Account')
                    @slot('name','bank_account')
                    @slot('placeholder','Bank Account')
                    @slot('value',$user->bank_account)
                    @endcomponent

                </div>
            </div>

            <div class="card">
               <div class="card-header">
                   Personal Info
               </div>
               <div class="card-block">



                @component('control.textbox')
                @slot('title','Personal Email')
                @slot('name','personal_email')
                @slot('placeholder','Personal Email')
                @slot('value',$user->personal_email)
                @endcomponent

                @component('control.textbox')
                @slot('title','Github Username')
                @slot('name','github')
                @slot('placeholder','Username')
                @slot('value',$user->github)
                @endcomponent

                @component('control.textbox')
                @slot('title','Twitter Username')
                @slot('name','twitter')
                @slot('placeholder','Username')
                @slot('value',$user->twitter)
                @endcomponent

            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary">Update</button>
    </div>

</div>
</form>
</div>
</div>
@endsection

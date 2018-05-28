@extends('layouts.app')


@section('header')
<script type="text/javascript" src={{ asset('library/datepicker/js/bootstrap-datepicker.js') }}></script>
<script type="text/javascript" src={{ asset('js/plugins/moment.min.js') }}></script>
<script type="text/javascript" src={{ asset('bower_components/select2/dist/js/select2.min.js') }}></script>
<link rel="stylesheet" type="text/css" href={{ asset('bower_components/select2/dist/css/select2.min.css') }}>
<link rel="stylesheet" type="text/css" href={{ asset('library/datepicker/css/datepicker.css') }}>
@endsection

@section('content')

<div class="container">
    <form action="{{ $route }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">


            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Profile Image
                    </div>
                    <div class="card-block">

                        <div class="admin-profile-image">
                            @isset($user)   

                            <img src="{{ url('avatars')}}/{{ $user->avatar }}" class="rounded" style="width:140px">
                            @endisset

                            <img src="{{ asset('images/defaultimage.png') }}" class="rounded uploadImage" style="width: 140px;height: 140px">

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
                        @slot('value', isset($user) ? $user->name : "")
                        @endcomponent

                        @component('control.textbox')
                        @slot('title','Email')
                        @slot('name','email')
                        @slot('placeholder','Enter Your Office Email')
                        @slot('value',isset($user) ? $user->email : "")
                        @endcomponent

                        @component('control.textbox')
                        @slot('title','Mobile')
                        @slot('name','mobile_no')
                        @slot('placeholder','Enter Your mobile number')
                        @slot('value',isset($user) ? $user->mobile_no : "")
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
                        @slot('value',isset($user) ? $user->get_join_date() : "")
                        @endcomponent

                        @component('control.datepicker')
                        @slot('title','Birthday')
                        @slot('id','birthday')
                        @slot('name','birthday')
                        @slot('value',isset($user) ? $user->get_birthday() : "")
                        @endcomponent

                        @if(Auth::user()->is_admin())
                        @component('control.textbox')
                        @slot('title','Salary')
                        @slot('name','salary')
                        @slot('placeholder','Enter Your Salary')
                        @slot('value',isset($user) ? $user->salary : "")
                        @endcomponent
                        @endif
                        

                    </div>
                </div>
            </div>




            <div class="col-md-6">    
                <div class="card">
                    <div class="card-header">
                        Password
                    </div>
                    <div class="card-block">

                        @component('control.textbox')
                        @slot('title','Password')
                        @slot('name','password')
                        @slot('type','password')
                        @slot('placeholder','Password')
                        @slot('value',"")
                        @endcomponent


                        @component('control.textbox')
                        @slot('title','Confirm Password')
                        @slot('name','password_confirmation')
                        @slot('type','password')
                        @slot('placeholder','Confirm Password')
                        @slot('value',"")
                        @endcomponent
                    </div>
                </div>
                @if(Auth::user()->is_admin())
                <div class="card">
                    <div class="card-header">
                        Time-Off
                    </div>
                    <div class="card-block">

                        @component('control.textbox')
                        @slot('title','Leave')
                        @slot('name','no_of_leave')
                        @slot('placeholder','No. Of Leave')
                        @slot('value',isset($user) ? $user->no_of_leave : "")
                        @endcomponent

                        @component('control.textbox')
                        @slot('title','Sick Leave')
                        @slot('name','sick_leave')
                        @slot('placeholder','5')
                        @slot('value',isset($user) ? $user->sick_leave : "")
                        @endcomponent

                       {{--  @component('control.textbox')
                        @slot('title','Urgent Leave')
                        @slot('name','urgent_leave')
                        @slot('placeholder','5')
                        @slot('value',isset($user) ? $user->urgent_leave : "")
                        @endcomponent --}}


                        <div class="form-group">

                            <label for="supervisor">Supervisor</label>
                            
                            <select id="supervisor-ajax" name="supervisor" style="width:100%">
                            <option value="{{ isset($user->supervisor) ? $user->supervisor->id : "" }}" selected="selected">{{ isset($user->supervisor) ? $user->supervisor->name : "" }}</option>
                            </select>

                            @if ($errors->has('supervisor'))
                                <span class="help-block">
                                <strong>{{ $errors->first('supervisor') }}</strong>
                                </span>
                            @endif

                        </div>

                        

                    </div>
                </div>

                @endif

                <div class="card">
                 <div class="card-header">
                     Bank Info
                 </div>
                 <div class="card-block">



                    @component('control.textbox')
                    @slot('title','Bank Name')
                    @slot('name','bank_name')
                    @slot('placeholder','Bank Name')
                    @slot('value',isset($user) ? $user->bank_name : "")
                    @endcomponent

                    @component('control.textbox')
                    @slot('title','Bank Account')
                    @slot('name','bank_account')
                    @slot('placeholder','Bank Account')
                    @slot('value',isset($user) ? $user->bank_account : "")
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
                @slot('value',isset($user) ? $user->personal_email : "")
                @endcomponent

                @component('control.textbox')
                @slot('title','Github Username')
                @slot('name','github')
                @slot('placeholder','Username')
                @slot('value',isset($user) ? $user->github : "")
                @endcomponent

                @component('control.textbox')
                @slot('title','Twitter Username')
                @slot('name','twitter')
                @slot('placeholder','Username')
                @slot('value',isset($user) ? $user->twitter : "")
                @endcomponent

                @component('control.textbox')
                @slot('title','Slack Username')
                @slot('name','slack')
                @slot('placeholder','Username')
                @slot('value',isset($user) ? $user->slack : "")
                @endcomponent

            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary">{{ $btn_title }}</button>
    </div>

</div>
</form>
</div>
</div>

<script>
    $("#supervisor-ajax").select2({
       placeholder: "Search Supervisor",
       minimumInputLength: 3,
       allowClear: true,
       ajax: {
        url: "{{route('user_ajax_search')}}",
        dataType: 'json',
        delay: 250,
        cache: true,
        processResults: function (data) {
            return {results: data };
        },

    }

});



    $(function (){

            @isset($user)
               $('.uploadImage').hide();
            @endisset

            function readURL(input) {
               if (input.files && input.files[0]) {
                   var reader = new FileReader();

                   reader.onload = function (e) {
                       $('.uploadImage').attr('src', e.target.result);
                   }

                   reader.readAsDataURL(input.files[0]);
               }
           }

           $(".custom-file-input").change(function(){
               readURL(this);
           });

    });   

</script>
@endsection

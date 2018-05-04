<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- bootstarp -->
    <script type="text/javascript" src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/bower_components/tether/dist/js/tether.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>


    <link rel="stylesheet" href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" />

    <!-- now UI Kit -->
    <link rel="stylesheet" href="{{ asset('/css/now-ui-kit.css') }}" />    
    <script type="text/javascript" src="{{ asset('/js/now-ui-kit.js') }}"></script>

    <!-- ionion icon -->
    <!-- Styles -->
    <link href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">

    @yield('header')

</head>
<body>
    <div id="app">

        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <div class="container">
              <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav mr-auto">
                     
                @if(Auth::guest())
                @else
                    @if(Auth::user()->is_admin())
                        <!-- user -->
                        @component('control.nav-item')
                            @slot('title','Employee')
                            @slot('add_route',route('add_user'))
                            @slot('list_route',route('user_list'))
                                
                        @endcomponent

                        <!-- ./users -->

                        <!-- Positions -->
                        @component('control.nav-item')
                            @slot('title','Office Locations')
                            @slot('add_route',route('location_add'))
                            @slot('list_route',route('location_list'))
                                
                        @endcomponent
                        
                         <!-- ./Posistions -->
                         
                         <!-- Positions -->
                         @component('control.nav-item')
                            @slot('title','Positions')
                            @slot('add_route',route('position_add'))
                            @slot('list_route',route('position_list'))
                                
                        @endcomponent
                         <!-- ./Posistions -->

                         <!-- time-off -->
                         <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Time-Off
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="{{ route('list_timeoff') }}">Requested</a>
        <a class="dropdown-item" href="{{ route('decided_timeoff_list') }}">List</a>

    </div>
</li>
                         <!-- ./time-off -->

                        

                     @endif
                     <li class="nav-item">
                        <a href="{{route('apply_timeoff')}}" class="nav-link">Apply Time-Off</a>
                     </li>
                 </ul>
                 <ul class="navbar-nav ml-auto">
                 <li class="nav-item dropdown" >
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     {{ Auth::user()->name }} 
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        </form>

                    </div>
                </li>
                @endif
</ul>

</div>
</div>
</nav>

        {{-- <nav class="navbar navbar-default navbar-static-top">
            <div class="container">


                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav> --}}

        @yield('content')
    </div>

    


</body>
</html>

@extends('layouts.app')
<style type="text/css">
  .btn:active{
    background-color: red;
    box-shadow: 0 5px maroon;
    transform: translateY(4px);
  }
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                Users
                <div class="right">

                  <form action="{{ route('user_search')}}">
                    <input type="text" name="name" placeholder="search" class="search-box" value="{{ isset($q) ? $q : "" }} ">
                </form>
                <a href="{{ route('add_user') }}" class="btn btn-primary icon-button"><span class="ion-plus"></span></a>

                </div>
                

                </div>


                <div class="card-block">
                     <table class="table table-striped">
  <thead>
    <tr>

      <th>Name</th>
      <th>Email</th>
      <th>Position</th>
      <th>Salary</th>
      <th>Time</th>
      <th>Actions</th>
      <th>Generate</th>
    </tr>
  </thead>
  <tbody>

                     @foreach ($users as $user)
                        @if ($user->id != $current_user->id)
                     <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->position->title }}</td>
                        <td>{{ $user->salary }}</td>
                        <td>{{$user->get_long_time_year_month()}}</td>
                        <td>
                        <a href="{{ route('user_profile',['id' => $user->id ]) }}" class="btn btn-info">Profile</a>
                        <a href="{{ route('edit_user',['id' => $user->id ]) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('del_user',['id'=> $user->id]) }}" class="btn btn-danger">Delete</a>
                        </td>
                        <td><a href="{{ route('generate_payslip',['id'=> $user->id]) }}" class="btn" style="background-color: purple;">PaySlip</a></td>
                        {{-- <td><a class="btn btn-info" href="{{ route('user_edit' , ['id' => $user->id]) }}">Edit</a>

                        <form class="inline" action="{{ route('user_delete', ['id' => $location->id]) }}" method="post">
                            {{ csrf_field() }}
                        <button class="btn btn-danger" href="{{ route('location_delete' , ['id' => $location->id]) }}">Delete</button></td> --}}
                        </form>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                    </table>


                </div>

            </div>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

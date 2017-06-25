@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                
                Users
                
                
                <a href="{{ route('add_user') }}" class="btn btn-primary card-icon-button"><span class="ion-plus"></span></a>
                
                </div>

               
                <div class="card-block">
                     <table class="table table-striped">
  <thead>
    <tr>
      
      <th>Name</th>
      <th>Email</th>
      <th>Posotion</th>
      <th>Location</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>

                     @foreach ($users as $user)
                        @if ($user->id != $current_user->id)
                     <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->position->title }}</td>

                        <td>{{ $user->location->name }}</td>
                        <td><a href="{{ route('edit_user',['id' => $user->id ]) }}" class="btn btn-primary">Edit</a></td>
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

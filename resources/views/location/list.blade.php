@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Office Locations</div>

               
                <div class="card-block">
                     <table class="table table-striped">
  <thead>
    <tr>
      
      <th>Location</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
                     @foreach ($locations as $location)
                     <tr>
                        <td>{{ $location->name }}</td>
                        <td><a class="btn btn-info" href="{{ route('location_edit' , ['id' => $location->id]) }}">Edit</a>

                        <form class="inline" action="{{ route('location_delete', ['id' => $location->id]) }}" method="post">
                            {{ csrf_field() }}
                        <button class="btn btn-danger" href="{{ route('location_delete' , ['id' => $location->id]) }}">Delete</button></td>
                        </form>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>


                </div>

            </div>
            {{ $locations->links() }}
        </div>
    </div>
</div>
@endsection

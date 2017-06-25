@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Positions</div>

               
                <div class="card-block">
                     <table class="table table-striped">
  <thead>
    <tr>
      
      <th>Position</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
                     @foreach ($positions as $position)
                     <tr>
                        <td>{{ $position->title }}</td>
                        <td><a class="btn btn-info" href="{{ route('position_edit' , ['id' => $position->id]) }}">Edit</a>

                        <form class="inline" action="{{ route('position_delete', ['id' => $position->id]) }}" method="post">
                            {{ csrf_field() }}
                        <button class="btn btn-danger" href="{{ route('position_delete' , ['id' => $position->id]) }}">Delete</button></td>
                        </form>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>


                </div>

            </div>
            {{ $positions->links() }}
        </div>
    </div>
</div>
@endsection

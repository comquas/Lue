@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                Time Off

                </div>


                <div class="card-block">
                     <table class="table table-striped">
  <thead>
    <tr>

      <th>Name</th>
      <th>From</th>
      <th>To</th>
      <th>No. Of Day</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>

                     @foreach ($leaves as $leave)

                     <tr>
                        <td>{{ $leave->user->name }}</td>
                        <td>{{ $leave->get_from_date() }}</td>
                        <td>{{ $leave->get_to_date() }}</td>
                        <td>{{ $leave->no_of_day }}</td>
                        <td><a href="{{ route('approve_timeoff',['id' => $leave->id]) }}" class="btn btn-primary">Approve</a>
                        <a href="#" class="btn btn-info">Edit</a>
                          <a href="#" class="btn btn-danger">Reject</a>
                          </td>

                        </form>
                        </tr>

                    @endforeach
                    </tbody>
                    </table>


                </div>

            </div>
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection

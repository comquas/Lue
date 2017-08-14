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
      <th>Type</th>
      <th>From</th>
      <th>To</th>
      <th>No. Of Day</th>
       @if(!isset($isDecidedLeave))
      <th>Actions</th>
      @if(isset($decision))
        <th>Actions</th>
      @else
      <th>Status</th>
      <th>Remark</th>
        <th>Status</th>
        <th>Remark</th>
      @endif
    </tr>
  </thead>
  <tbody>

                     @foreach ($leaves as $leave)

                     <tr>
                        <td>{{ $leave->user->name }}</td>
                        <td>@if($leave->type == 1)
                                Annual
                            @else
                                Sick
                            @endif
                        </td>
                        <td>{{ $leave->get_from_date() }}</td>
                        <td>{{ $leave->get_to_date() }}</td>
                        <td>{{ $leave->no_of_day }}</td>
                        <!-- isDecidedLeave is used -->
                        @if(!isset($isDecidedLeave))
                        @if(isset($decision))
                        <td><a href="{{ route('approve_timeoff',['id' => $leave->id]) }}" class="btn btn-primary">Approve</a>
                        <a href="{{ route('edit_timeoff',['id' => $leave->id]) }}" class="btn btn-info">Edit</a>
                         
                        <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">Reject
                        </button>
                        </td>
                        @else
                        <td>
                          @if($leave->status == 1)
                            Approved
                          @else
                          @elseif ($leave->status == 2)
                            Rejected
                          @endif
                        </td>
                        <td>
                          {{$leave->remark}}
                        </td>
                        @endif
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
<!-- Modal Core -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Remark</h4>
      </div>
      <div class="modal-body">
         <form method="post"  action={{ route('reject_timeoff') }}>
         {{csrf_field()}}

            <input type="text" name="leave_id" value={{isset($leave)?$leave->id:''}} hidden>
            <div class="form-group">
            <textarea class="form-control" name="remark"></textarea>
            </div>
            <input type="submit" id="submit" hidden>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info btn-simple" id="save">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
      $(document).ready(function(){
        $("#save").click(function(){
        $("#submit").click();
        });
      });
</script>
@endsection

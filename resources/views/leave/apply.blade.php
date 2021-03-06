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
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">

					Apply Leave

				</div>
				<div class="card-block">

					<form action="{{ $route }}" method="post">
						{{ csrf_field() }}

						<div class="form-group">
							<label for="title">Type</label>
							<select id="type" name="type" style="width:100%;display: block;">

								<option value=1 {{ !isset($leave) && $leave->type == 1 ? "selected" : "" }}>Annual</option>
								<option value=2 {{ !isset($leave) && $leave->type == 2 ? "selected" : "" }}>Sick</option>
								
							</select>
						</div>
						<div class="row">
							<div class="col-md-6">
								@component('control.textbox')
								@slot('title','From')
								@slot('name','from_date')
								@slot('placeholder','')
								@if(isset($leave))
								@slot('value',Carbon\Carbon::parse($leave->from)->format('d-m-Y'))
								@else
								@slot('value',"")
								@endif
								@endcomponent
							</div>
							<div class="col-md-6">
								@component('control.textbox')
								@slot('title','To')
								@slot('name','to_date')
								@slot('placeholder','')
								@if(isset($leave))
								@slot('value',Carbon\Carbon::parse($leave->to)->format('d-m-Y'))
								@else
								@slot('value',"")
								@endif
								@endcomponent
							</div>
						</div>



						@component('control.textbox')
						@slot('title','No. Of Day')
						@slot('name','no_of_day')
						@slot('placeholder','1')
						@if(isset($leave))
						@slot('value',$leave_days+1)
						@else
						@slot('value',"")
						@endif
						@endcomponent

						<div class="form-group" id="zero_five">
							<input type="checkbox" id="zero_point"> check for 0.5 day
						</div>



						<div class="form-group @if($errors->has('reason')) has-error @endif">
							<label for="reason">Reason</label><br>
							<textarea name="reason" class="form-control" 
							style="width: 100%">@isset($leave){{$leave->reason}}@endisset</textarea>
							@if($errors->has('reason')) <b><div class="help-block">{{$errors->first('reason')}}</div></b>  @endif
						</div>

						<button class="btn btn-primary">{{ $btn_title }}</button>

					</form>

				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">

	$(document).ready(function() {

	    $('#zero_five').hide();
	    $("#type").select2();


		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

		var checkin = $('#from_date').datepicker({
			format: "dd-mm-yyyy",
			onRender: function(date) {
				return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			if (ev.date.valueOf() > checkout.date.valueOf()) {
				var newDate = new Date(ev.date)
				newDate.setDate(newDate.getDate() + 1);
				checkout.setValue(newDate);
			}
			checkin.hide();
			$('#to_date')[0].focus();
		}).data('datepicker');

		var checkout = $('#to_date').datepicker({
			format: "dd-mm-yyyy",
			onRender: function(date) {
				//console.log(date.valueOf());
				return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			var a = moment(checkin.date);
			var b = moment(checkout.date);
			var day = $("#no_of_day").val();
			//console.log(day);
			if(day>0) {
                $("#no_of_day").val('');
            }



			if($("#no_of_day").val(workday_count(a,b)).val() < 2){

			    $('#zero_five').show();

                $('#zero_point').bind('change', function(e) {
                    if ($(this).is(':checked')) {
                       $('#no_of_day').val('0.5')
                    }
                    else {
                        $("#no_of_day").val(workday_count(a,b)).val();
                    }
                })
			}else{
				$('#zero_five').hide();
			}

			checkout.hide();

		}).data('datepicker');
	});


	function workday_count(start,end) {
  		var first = start.clone().endOf('week'); // end of first week
  		var last = end.clone().startOf('week'); // start of last week
  		var days = last.diff(first,'days') * 5 / 7; // this will always multiply of 7
  		var wfirst = first.day() - start.day(); // check first week
  		if(start.day() == 0) --wfirst; // -1 if start with sunday
  		var wlast = end.day() - last.day(); // check last week
  		if(end.day() == 6) --wlast; // -1 if end with saturday
  		return Math.trunc(wfirst + days + wlast); // get the total
	}

</script>

@endsection

<div class="form-group">

	<label for="{{ $id }}">{{ $title }}</label>
	

		<input type="text" class="form-control date-picker" name="{{ $name }}" id="{{ $id }}" value="{{ old($name,$value) }}" data-datepicker-color="">

		

	@if ($errors->has($name))
	<span class="help-block">
		<strong>{{ $errors->first($name) }}</strong>
	</span>
	@endif

	<script type="text/javascript">
		
	</script>

</div>
<div class="form-group">

	<label for="{{ $title }}">{{ $title }}</label>
	<input type="{{ isset($type) ? $type : 'text' }}" class="form-control" name="{{ $name }}" class="form-control" id="{{ $title }}" placeholder="{{ $placeholder }}" value="{{ old($name,$value) }}">

	@if ($errors->has($name))
	<span class="help-block">
		<strong>{{ $errors->first($name) }}</strong>
	</span>
	@endif

</div>
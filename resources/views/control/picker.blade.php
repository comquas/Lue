<div class="form-group">

<label for="{{ $name }}">{{ $title }}</label>
    <select name="{{ $name }}" class="form-control">
        @foreach ($objects as $object)
        @if ($object->id == $selected_id)
        <option selected value="{{ $object->id }}">{{ $object->$type }}</option>
        @else
        <option value="{{ $object->id }}">{{ $object->$type }}</option>
        @endif
        @endforeach
    </select>
</div>
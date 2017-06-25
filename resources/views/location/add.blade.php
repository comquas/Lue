@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Add Office Location</div>

                <div class="card-block">
                   
                   <form action="{{ $route }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name">Office Location Name</label>
                        <input type="text" class="form-control" name="name" class="form-control" id="name" placeholder="" required value=" {{ $location->name }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <button class="btn btn-primary">{{ $btn_title }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

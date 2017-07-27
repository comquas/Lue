@extends('layouts.app')



@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Position</div>

                <div class="card-block">
                    
                   <form action="{{ $route }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="title">Position Name</label>
                        <input type="text" class="form-control" name="title" class="form-control" id="title" placeholder="Web Developer" required value=" {{ $position->title }}">
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="level">Level</label>
                        <input type="text" class="form-control" name="level" class="form-control" id="level" placeholder="1" required value=" {{ $position->level }}">
                        @if ($errors->has('level'))
                            <span class="help-block">
                                <strong>{{ $errors->first('level') }}</strong>
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

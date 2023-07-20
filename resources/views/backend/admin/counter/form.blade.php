@extends('layouts.backend')
@section('title', trans('app.add_counter'))

@section('content')
<div class="card shadow" id="printMe">

    <div class="card-header bg-danger text-white">
        <h3 class="card-title">{{ trans('app.add_counter') }}</h3>
    </div>

    <div class="card-body"> 

        {{ Form::open(['url' => 'admin/counter/create', 'class'=>'col-md-7 col-sm-8']) }}

            <div class="form-group @error('name') has-error @enderror">
                <label for="name">{{ trans('app.name') }} <i class="text-danger">*</i></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ trans('app.name') }}" value="{{ old('name') }}">
                <span class="text-danger">{{ $errors->first('name') }}</span>
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label for="description">{{ trans('app.description') }} </label> 
                <textarea name="description" id="description" class="form-control" placeholder="{{ trans('app.description') }}">{{ old('description') }}</textarea>
                <span class="text-danger">{{ $errors->first('description') }}</span>
            </div>

            <div class="form-group @error('status') has-error @enderror">
                <label for="status">{{ trans('app.status') }} <i class="text-danger">*</i></label>
                <div id="status"> 
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" {{ (old("status")==1)?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" {{ (old("status")==0)?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div>  

            <div class="form-group">
    <div class="row">
        <div class="col">
                <button class="btn btn-danger btn-block mr-2" type="reset">{{ trans('app.reset') }}</button>
                </div>
        <div class="col">
                <button class="btn btn-success btn-block" type="submit">{{ trans('app.save') }}</button>
            </div>

        {{ Form::close() }}

    </div>
</div> 

@endsection

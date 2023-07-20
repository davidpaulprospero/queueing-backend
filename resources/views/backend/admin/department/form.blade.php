@extends('layouts.backend')
@section('title', trans('app.add_department'))

@section('content')
<div class="card shadow">
    <div class="card-header bg-danger text-white">
        <h3 class="card-title">{{ trans('app.add_department') }}</h3>
    </div>
    <div class="card-body">
            
      
    <div class="card-body">
    {{ Form::open(['url' => 'admin/department/create', 'class'=>'col-md-7 col-sm-8']) }}

    <div class="form-group">
        <label for="name">{{ trans('app.name') }} <i class="text-danger">*</i></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ trans('app.name') }}" value="{{ old('name') }}">
        @error('name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="description">{{ trans('app.description') }}</label>
        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="{{ trans('app.description') }}">{{ old('description') }}</textarea>
        @error('description')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="key">{{ trans('app.key_for_keyboard_mode') }} <i class="text-danger">*</i></label><br>
        {{ Form::select('key', $keyList, null, ['placeholder' => trans('app.select_option'), 'class'=>'select2 form-control']) }}<br>
        @error('key')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">{{ trans('app.status') }} <i class="text-danger">*</i></label>
        <div id="status">
            <label class="radio-inline">
                <input type="radio" name="status" value="1" {{ old("status")==1 ? "checked" : "" }}> {{ trans('app.active') }}
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" value="0" {{ old("status")==0 ? "checked" : "" }}> {{ trans('app.deactive') }}
            </label>
        </div>
        @error('status')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Add any additional form fields or content here -->

</div>
</div>


            <div class="form-group">
    <div class="row">
        <div class="col">
            <button class="btn btn-danger btn-block" type="reset">{{ trans('app.reset') }}</button>
        </div>
        <div class="col">
            <button class="btn btn-success btn-block" type="submit">{{ trans('app.save') }}</button>
        </div>
        {{ Form::close() }}
        
    </div>
</div> 
@endsection

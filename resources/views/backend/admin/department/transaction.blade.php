@extends('layouts.backend')
@section('title', trans('app.transaction_type'))

@section('content')
<div class="card" id="printMe">
    <div class="card-header bg-danger text-white">
        <div class="row">
            <div class="col-12 text-left">
                <h3>{{ trans('app.transaction_type') }}</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-7 col-sm-8">
                {{ Form::open(['route' => ['admin.department.transaction.store']]) }}

                <div class="form-group">
                    <label for="department_id">{{ trans('app.department') }}</label>
                    <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">{{ trans('Transaction Name') }}</label>
                    <textarea name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ trans('Transaction Name') }}">{{ old('name') }}</textarea>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                    <!-- Key input -->
                <!-- <div class="form-group">
                    <label for="key">{{ trans('app.key_for_keyboard_mode') }}</label>
                    <select name="key" id="key" class="form-control" required>
                        <option value="">-- {{ trans('app.select_key') }} --</option>
                        @foreach($keyList as $key)
                            <option value="{{ $key }}">{{ $key }}</option>
                        @endforeach
                    </select>
                </div> -->
          

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
    </div>
</div>


@endsection

@extends('layouts.backend')
@section('title', trans('app.update_department')) 

@section('content')
<div class="panel panel-primary" id="printMe">

    <div class="panel-heading" >
        <div class="row">
            <div class="col-sm-12 text-left">
                <h3>{{ trans('app.update_department') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body" > 

        {{ Form::open(['url' => 'admin/department/edit', 'class'=>'col-md-7 col-sm-8']) }}

            <input type="hidden" name="id" value="{{ $department->id }}">

            <div class="form-group @error('name') has-error @enderror">
                <label for="name">{{ trans('app.name') }} <i class="text-danger">*</i></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ trans('app.name') }}" value="{{ old('name')?old('name'):$department->name }}">
                <span class="text-danger">{{ $errors->first('name') }}</span>
            </div>

            <div class="form-group @error('description') has-error @enderror">
                <label for="description">{{ trans('app.description') }} </label> 
                <textarea name="description" id="description" class="form-control" placeholder="{{ trans('app.description') }}">{{ old('description')?old('description'):$department->description }}</textarea>
                <span class="text-danger">{{ $errors->first('description') }}</span>
            </div>

            <div class="form-group @error('key') has-error @enderror">
                <label for="key">Key</label><br/>
                {{ Form::select('key', $keyList, (old("key")?old("key"):$department->key), ['placeholder' => trans('app.select_option'), 'class'=>'select2 form-control']) }}<br/>
                <span class="text-danger">{{ $errors->first('key') }}</span>
            </div>

            <div class="form-group @error('status') has-error @enderror">
                <label for="status">{{ trans('app.status') }} <i class="text-danger">*</i></label>
                <div id="status"> 
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" {{ ((old('status') || $department->status)==1)?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" {{ ((old('status') || $department->status)==0)?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div>

            <div class="form-group">
                <label for="transaction_type">Edit Transaction Type </label><br/>
                <label for="transaction_type">Click to Edit Name</label>
                <ul>
                    @foreach($department->transactionTypes as $transactionType)
                        <li>
                            <span class="transaction-type-name">{{ $transactionType->name }}</span><br/>
                            <label for="transaction_type">Key</label>
                            <select name="transaction_type_key[{{ $transactionType->id }}]">
                                @foreach($keyList as $key)
                                    <option value="{{ $key }}" {{ old('transaction_type.'.$transactionType->id, $transactionType->key) == $key ? 'selected' : '' }}>
                                        {{ $key }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" class="edit-transaction-type" name="transaction_type[{{ $transactionType->id }}]" value="{{ old('transaction_type.'.$transactionType->id, $transactionType->name) }}" style="display: none;">
                        </li>
                    @endforeach
                </ul>
            </div>


            <div class="form-group">
                <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
                <button class="button btn btn-success" type="submit"><span>{{ trans('app.update') }}</span></button> 
            </div>

        {{ Form::close() }}
    </div>
</div> 
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var transactionTypeNames = document.querySelectorAll('.transaction-type-name');
        var editFields = document.querySelectorAll('.edit-transaction-type');

        for (var i = 0; i < transactionTypeNames.length; i++) {
            transactionTypeNames[i].addEventListener('click', function() {
                var listItem = this.parentNode;
                var transactionTypeName = this;
                var editField = listItem.querySelector('.edit-transaction-type');

                if (editField.style.display === 'none') {
                    transactionTypeName.style.display = 'none';
                    editField.style.display = 'inline-block';
                    editField.value = transactionTypeName.textContent;
                } else {
                    transactionTypeName.textContent = editField.value;
                    transactionTypeName.style.display = 'inline-block';
                    editField.style.display = 'none';
                }
            });
        }
    });
</script>
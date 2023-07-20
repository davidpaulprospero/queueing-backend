@extends('layouts.backend')
@section('title', trans('app.display_setting'))

@section('content')
<div class="card ">
    <div class="card-header bg-danger text-white">
        <div class="row align-items-center">
            <div class="col">
                <h3>{{ trans('app.display_setting') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body"> 

        {{ Form::open(['url' => 'admin/setting/display', 'enctype' => 'multipart/form-data']) }}

        <input type="hidden" name="id" value="{{ $setting->id }}">
     
        <div class="col-sm-6">

            <div class="form-group @error('display') has-error @enderror">
                <?php 
                    $display = [
                        '1' => trans('app.display_1'),
                        '2' => trans('app.display_2'),
                        '3' => trans('app.display_3'), 
                        '4' => trans('app.display_4'), 
                        '5' => trans('app.display_5')
                    ]; 
                ?>
                <label for="display">{{ trans('app.display') }} </label><br/>
                {{ Form::select('display', $display , $setting->display , ['placeholder' => trans('app.select_option'), 'class'=>'select2 form-control']) }}<br/>
                <span class="text-danger">{{ $errors->first('display') }}</span>
            </div> 

            <div class="form-group @error('message') has-error @enderror">
                <label for="message">{{ trans('app.message') }}</label> 
                <textarea type="text" name="message" id="message" class="form-control" placeholder="{{ trans('app.message') }}">{{ $setting->message }}</textarea>
                <span class="text-danger">{{ $errors->first('message') }}</span>
            </div> 
                        <div class="form-group @error('video') has-error @enderror">
                <label for="video">{{ trans('app.upload_video') }}</label>
                <input type="file" name="video" id="video" class="form-control">
                <span class="text-danger">{{ $errors->first('video') }}</span>
                @if ($setting->video)
                    <video width="320" height="240" controls>
                        <source src="{{ asset('public/videos/' . $setting->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
            </div>
 
            <div class="form-group @error('direction') has-error @enderror">
                <label for="direction">{{ trans('app.direction') }}</label>
                <div id="direction">  
                    <label class="radio-inline">
                        <input type="radio" name="direction" value="left" {{ (($setting->direction)=='left')?"checked":"" }}> {{ trans('app.left') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="direction" value="right" {{ (($setting->direction)=='right')?"checked":"" }}> {{ trans('app.right') }}
                    </label> 
                </div>
            </div> 
 
            <div class="form-group @error('time_format') has-error @enderror">
                <label for="time_format">{{ trans('app.time_format') }} </label><br/>
                {{ Form::select('time_format', ['h:i:s A' => '12 Hour', 'H:i:s' => '24 Hour'], $setting->time_format , ['id'=>'time_format', 'class'=>'select2 form-control']) }}<br/>
                <span class="text-danger">{{ $errors->first('time_format') }}</span>
            </div> 

            <div class="form-group @error('date_format') has-error @enderror">
                <?php 
                    $dates = [
                        'd M, Y' => date('d M, Y'),
                        'F j, Y' => date('F j, Y'),
                        'd/m/Y'  => date('d/m/Y'),
                        'm.d.y'  => date('m.d.y') 
                    ]; 
                ?>
                <label for="date_format">{{ trans('app.date_format') }} </label><br/>
                {{ Form::select('date_format', $dates , $setting->date_format , ['placeholder' => trans('app.select_option'), 'id'=>'date_format', 'class'=>'select2 form-control']) }}<br/>
                <span class="text-danger">{{ $errors->first('date_format') }}</span>
            </div> 

            <div class="form-group @error('color') has-error @enderror">
                <label for="color">{{ trans('app.color') }}</label> 
                <input type="color" name="color" id="color" class="form-control" placeholder="{{ trans('app.color') }}" value="{{ $setting->color }}">
                <span class="text-danger">{{ $errors->first('color') }}</span>
            </div>

            <div class="form-group @error('background_color') has-error @enderror">
                <label for="background_color">{{ trans('app.background_color') }}</label> 
                <input type="color" name="background_color" class="form-control" id="background_color" placeholder="{{ trans('app.background_color') }}" value="{{ $setting->background_color }}">
                <span class="text-danger">{{ $errors->first('background_color') }}</span>
            </div>

            <div class="form-group @error('border_color') has-error @enderror">
                <label for="border_color">{{ trans('app.border_color') }}</label>
                <input type="color" name="border_color" id="border_color" class="form-control" placeholder="{{ trans('app.border_color') }}" value="{{ $setting->border_color }}"> 
                <span class="text-danger">{{ $errors->first('border_color') }}</span>
            </div>
        </div>
  

        <div class="col-sm-6">
            <div class="form-group @error('delete_video') has-error @enderror">
                <label for="delete_video">{{ trans('app.delete_video') }}</label>
                @if ($setting->video)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="delete_video" value="1">
                            {{ trans('app.delete_existing_video') }}
                        </label>
                    </div>
                @endif
                <span class="text-danger">{{ $errors->first('delete_video') }}</span>
            </div>
 
            <div class="form-group">
                <label for="autoplay">{{ trans('app.autoplay') }}</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="autoplay" value="1" {{ $setting->autoplay ? 'checked' : '' }}>
                        {{ trans('app.enable_autoplay') }}
                    </label>
                </div>
                <span class="text-danger">{{ $errors->first('autoplay') }}</span>
            </div>

            <div class="form-group @error('alert_position') has-error @enderror">
                <label for="alert_position">{{ trans('app.alert_position') }} <span>(Position of Waiting Before Process)</span></label>
                <input type="text" name="alert_position" id="alert_position" class="form-control" placeholder="{{ trans('app.alert_position') }}" value="{{ $setting->alert_position }}">
                <span class="text-danger">{{ $errors->first('alert_position') }}</span>
            </div>

            <div class="form-group @error('sms_alert') has-error @enderror">
                <label for="sms_alert">{{ trans('app.sms_alert') }}</label>
                <div id="sms_alert">  
                    <label class="radio-inline">
                        <input type="radio" name="sms_alert" value="1" {{ (($setting->sms_alert)=='1')?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="sms_alert" value="0" {{ (($setting->sms_alert)=='0')?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div> 

            <div class="form-group @error('show_officer') has-error @enderror">
                <label for="show_officer">{{ trans('app.show_officer') }}</label>
                <div id="show_officer">  
                    <label class="radio-inline">
                        <input type="radio" name="show_officer" value="1" {{ (($setting->show_officer)=='1')?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="show_officer" value="0" {{ (($setting->show_officer)=='0')?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div> 

            <div class="form-group @error('show_department') has-error @enderror">
                <label for="show_department">{{ trans('app.show_department') }}</label>
                <div id="show_department">  
                    <label class="radio-inline">
                        <input type="radio" name="show_department" value="1" {{ (($setting->show_department)=='1')?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="show_department" value="0" {{ (($setting->show_department)=='0')?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div> 

            <div class="form-group @error('show_note') has-error @enderror">
                <label for="show_note">{{ trans('app.show_note') }}</label>
                <div id="show_note">  
                    <label class="radio-inline">
                        <input type="radio" name="show_note" value="1" {{ (($setting->show_note)=='1')?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="show_note" value="0" {{ (($setting->show_note)=='0')?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div> 

            <div class="form-group @error('keyboard_mode') has-error @enderror">
                <label for="keyboard_mode">{{ trans('app.keyboard_mode') }}</label>
                <div id="keyboard_mode">  
                    <label class="radio-inline">
                        <input type="radio" name="keyboard_mode" value="1" {{ (($setting->keyboard_mode)=='1')?"checked":"" }}> {{ trans('app.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="keyboard_mode" value="0" {{ (($setting->keyboard_mode)=='0')?"checked":"" }}> {{ trans('app.deactive') }}
                    </label> 
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <button class="button btn btn-danger btn-block" type="reset"><span>{{ trans('app.reset') }}</span></button>
                    </div>
                    <div class="col">
                        <button class="button btn btn-success btn-block" type="submit"><span>{{ trans('app.update') }}</span></button> 
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}

    </div> 
</div> 

<!-- Custom Display -->
<div class="panel panel-primary"> 
    <div class="panel-heading"> 
        <ul class="row list-inline m-0">
            <li class="col-xs-10 p-0 text-left">
                <h3>{{ trans('app.custom_display') }}</h3>
            </li>
            <li class="col-xs-2 p-0 text-right">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".customDisplayModal" data-id="" title="{{ trans('app.add_display') }}">
                    <i class="fa fa-plus"></i>
                </button>
            </li>
        </ul> 
    </div>

    <div class=" panel-body">  
        <table class="datatable table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('app.name') }}</th>
                    <th>{{ trans('app.counter') }}</th>
                    <th>{{ trans('app.description') }}</th>
                    <th>{{ trans('app.status') }}</th>
                    <th><i class="fa fa-cogs"></i></th> 
                </tr>
            </thead>
            <tbody>
                @foreach($customDisplays as $display) 
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $display->name }}</td>
                    <td>
                    @if (!empty($display->counters))
                    @foreach(explode(',', $display->counters) as $c) 
                        @if(!empty($counters[$c]))
                            <span class="label label-success">{{ $counters[$c] }}</span>&nbsp;
                        @endif 
                    @endforeach
                    @endif
                    </td>
                    <td>{{ $display->description }}</td>
                    <td>{!! (($display->status==1)?"<span class='label label-success'>". trans('app.active') ."</span>":"<span class='label label-danger'>". trans('app.deactive') ."</span>") !!}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".customDisplayModal" title="{{ trans('app.update_display') }}" data-id="{{ $display->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <a href="{{ url('common/display?type=6') }}&custom={{ $display->id }}" target="_blank" class="btn btn-success btn-sm" title="{{ trans('app.display') }}">
                            <i class="fa fa-desktop"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table> 
    </div>
</div>

<!-- Modal -->
<div class="modal fade customDisplayModal" tabindex="-1" role="dialog" aria-labelledby="customDisplayModalLabel">
  <div class="modal-dialog" role="document"> 
    {{ Form::open(['url' => 'admin/setting/display/custom', 'class'=>'modal-content',  'id'=>'customFrm']) }}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="customDisplayModalLabel"><strong></strong> <?= trans('app.custom_display') ?></h4>
      </div>
      <div class="modal-body">  
        <div class="alert mb-1"></div>

        <input type="hidden" name="id" value="">

        <div class="form-group">
            <label for="name">{{ trans('app.name') }} <i class="text-danger">*</i></label> 
            <input type="text" name="name" id="name" class="form-control" placeholder="eg:- Floor 1">
            <span class="text-danger"></span>
        </div>

        <div class="form-group">
            <label for="description">{{ trans('app.description') }}</label> 
            <textarea type="text" name="description" id="description" class="form-control" placeholder="{{ trans('app.description') }}"></textarea>
            <span class="text-danger"></span>
        </div>

        <div class="form-group">
            <label for="counters">{{ trans('app.counter') }} <i class="text-danger">*</i></label><br/>
            {{ Form::select('counters[]', $counters, null, ['id'=>'counters', 'class'=>'select2 form-control', 'multiple'=>'true']) }}<br/>
            <span class="text-danger"></span>
        </div> 
   
        <div class="form-group">
            <label for="status">{{ trans('app.status') }} <i class="text-danger">*</i></label>
            <div id="status"> 
                <label class="radio-inline">
                    <input type="radio" name="status" value="1" checked> {{ trans('app.active') }}
                </label>
                <label class="radio-inline">
                    <input type="radio" name="status" value="0"> {{ trans('app.deactive') }}
                </label> 
            </div>
            <span class="text-danger"></span>
        </div>   
      </div>
      <div class="modal-footer"> 
            <button class="button btn btn-info" type="reset"><span>{{ trans('app.reset') }}</span></button>
            <button class="button btn btn-success" type="submit"><span>{{ trans('app.save') }}</span></button>  
      </div>
    {{ Form::close() }}
  </div>
</div> 
@endsection


@push("scripts")
<script type="text/javascript">
$(document).ready(function(){

    // ready modal form
    $('.customDisplayModal').on('show.bs.modal', function (event) {
        var button    = $(event.relatedTarget);
        var id        = button.data('id');
        var modal     = $(this);
        modal.find('form').get(0).reset();
        modal.find('.alert').hide();
        modal.find('.form-group').removeClass('has-error');
        modal.find('.form-group').find('span.text-danger').html('');
        modal.find('form').attr('action', "{{ url('admin/setting/display/custom') }}");
        if(id!=''){
            modal.find('.modal-title strong').html('<?= trans('app.edit') ?>');
            modal.find('form').append('{{ method_field("PATCH") }}');
            modal.find('input[name="id"]').val(id);
            modal.find('form').attr('action', "{{ url('admin/setting/display/custom') }}/"+id);
            $.ajax({
                url : "{{ url('admin/setting/display/custom') }}/"+id,
                method : "GET",
                dataType: 'JSON',
                success:function(result){ 
                    if(result){
                        modal.find('input[name="name"]').val(result.name);
                        modal.find('textarea[name="description"]').val(result.description);
                        modal.find('select[name="counters[]"]').val(result.counters.split(','));
                        modal.find('input[name="status"][value="'+result.status+'"]').prop('checked', true);
                    }
                }
            });
        }else{
            modal.find('.modal-title strong').html('<?= trans('app.add') ?>');
        }
    });

    // store data
    $('#customFrm').on('submit', function(e){
        e.preventDefault();
        var form   = $(this);
        var url    = form.attr('action');
        var method = form.attr('method');
        form.find('.alert').hide();
        form.find('.form-group').removeClass('has-error');
        form.find('.form-group').find('span.text-danger').html('');
        $.ajax({
            url : url,
            method : method,
            data : form.serialize(),
            success:function(result){
                if(result.status == 'fail'){
                    form.find('.alert').show().addClass('alert-danger').html(result.message);
                    if(result.validator != undefined){
                        $.each(result.validator, function(key, value){
                            form.find('input[name="'+key+'"]').parent().addClass('has-error');
                            form.find('input[name="'+key+'"]').parent().find('span.text-danger').html(value);
                            form.find('textarea[name="'+key+'"]').parent().addClass('has-error');
                            form.find('textarea[name="'+key+'"]').parent().find('span.text-danger').html(value);
                            form.find('select[name="'+key+'"]').parent().addClass('has-error');
                            form.find('select[name="'+key+'"]').parent().find('span.text-danger').html(value);
                        });
                    }
                }else if(result.status == 'success'){
                    form[0].reset();
                    form.find('.alert').show().addClass('alert-success').html(result.message);
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }
        });
    });

});
</script>
@endpush

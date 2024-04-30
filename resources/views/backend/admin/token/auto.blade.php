@extends('layouts.backend')
@section('title', trans('app.auto_token'))

@section('content')
<!-- <script src="{{ asset('node_modules/push.js/bin/push.min.js') }}"></script> -->
<div class="card ">
    <div class="card-header bg-danger text-white">
        <div class="row align-items-center">
            <div class="col">
            </div> 
        
    </div>
    
        <ul class="row m-0 list-inline" >
            <li class="col-xs-6 col-sm-4 p-0 text-left">
              
            </li>  
            <li class="col-xs-4 col-sm-4 hidden-xs" id="screen-title">
                <h3 class="mt-1 pt-1">{{ trans('app.auto_token') }}</h3>
            </li>              
            <li class="col-xs-6 col-sm-4 p-1 text-right">
                <div class="mt-1 pt-1">
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#infoModal">
                      <i class="fa fa-info-circle"></i>
                    </button>
                    @if ($display->keyboard_mode)
                    <div class="disabled btn btn-success btn-sm" title="Keyboard Mode Enable">
                        <i class="fa fa-keyboard-o"></i>&nbsp;&nbsp;<i class="fa fa-check"></i>
                    </div> 
                    @else
                    <div class="disabled btn btn-danger btn-sm" title="Keyboard Mode Disabled">
                        <i class="fa fa-keyboard-o"></i>&nbsp;&nbsp;<i class="fa fa-times"></i>
                    </div> 
                    @endif
                    <button id="toggleScreen" class="btn btn-sm btn-primary"><i class="fa fa-arrows-alt"></i></button>
                </div> 
            </li> 
        </ul>
    </div>   

    <div class="panel-body">
        <div class="col-sm-12" id="screen-content">
            @if($display)
                @foreach ($departmentList as $department )
                    {{ Form::open(['url' => 'admin/token/auto', 'class' => 'AutoFrm p-1 m-1 btn btn-primary capitalize text-center']) }} 
                    <input type="hidden" name="department_id" value="{{ $department->department_id }}">
                    <input type="hidden" name="counter_id" value="{{ $department->counter_id }}">
                    <input type="hidden" name="user_id" value="{{ $department->user_id }}">
                    <button 
                        type="submit" 
                        class="p-1 m-1 btn btn-primary capitalize text-center"
                        style="min-width: 15vw;white-space: pre-wrap;box-shadow:0px 0px 0px 2px#<?= substr(dechex(crc32($department->name)), 0, 6); ?>" 
                        data-token="{{ $department->token }}"
                        data-queue="{{ $department->queue }}"
                        >
                        <h5>{{ $department->name }}</h5>
                        <h6>{{ $department->officer }}</h6>
                    </button> 
                    {{ Form::close() }}
                    @endforeach 

                                    <!--Ends of Without Mobile No -->
            @endif
        </div>  
    </div> 
</div>  

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="infoModalLabel"><?= trans('app.note') ?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 

<div class="modal fade" tabindex="-1" id="tokenModal" role="dialog" style="z-index:100000">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      {{ Form::open(['url' => 'admin/token/auto', 'class' => 'AutoFrm']) }} 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('app.user_information') }}</h4>
      </div>
      <div class="modal-body">

        @if($display->show_note)
            <p>
                <textarea name="note" id="note" class="form-control" placeholder="{{ trans('app.note') }}">{{ old('note') }}</textarea>
                <span class="text-danger">The Note field is required!</span>
            </p>
        @endif

        <input type="hidden" name="department_id">
        <input type="hidden" name="counter_id">
        <input type="hidden" name="user_id">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success hidden">{{ trans('app.submit') }}</button>
      </div>
      {{ Form::close() }}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@push("scripts")
<script type="text/javascript">
(function($) {
    $('.modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('input[name=department_id]').val(button.data('department-id'));
        $('input[name=counter_id]').val(button.data('counter-id'));
        $('input[name=user_id]').val(button.data('user-id'));

        $("input[name=client_mobile]").val("");
        $("textarea[name=note]").val("");
        $('.modal button[type=submit]').addClass('hidden');
    });

    $('.modal').on('hide.bs.modal', function () {
        $('.modal-backdrop').remove();
    });

    $("input[name=client_mobile], textarea[name=note]").on('keyup change', function(e){
        var valid = false;
        var mobileErrorMessage = "";
        var noteErrorMessage = "";
        var mobile = $('input[name=client_mobile]').val();
        var note   = $('textarea[name=note]').val();

        if ($('input[name=client_mobile]').length)
        {
            if (mobile == '')
            {
                mobileErrorMessage = "The Mobile No. field is required!";
                valid = false;
            } 
            else if(!$.isNumeric(mobile)) 
            {
                mobileErrorMessage = "The Mobile No. is incorrect!";
                valid = false;
            }
            else if (mobile.length >= 15 || mobile.length < 7)
            {
                mobileErrorMessage = "The Mobile No. must be between 7-15 digits";
                valid = false;
            } 
            else
            { 
                mobileErrorMessage = "";
                valid = true;
            }   
        }   

        if ($('textarea[name=note]').length)
        {
            if (note == '')
            {
                noteErrorMessage = "The Note field is required!";
                valid = false;
            }
            else if (note.length >= 255 || note.length < 0)
            {
                noteErrorMessage = "The Note must be between 1-255 characters";
                valid = false;
            }
            else
            {
                noteErrorMessage = "";
                valid = true;
            }
        }


        if(!valid && mobileErrorMessage.length > 0)
        {
            $('.modal button[type=submit]').addClass('hidden');
        } 
        else if(!valid && noteErrorMessage.length > 0)
        {
            $('.modal button[type=submit]').addClass('hidden');
        } 
        else
        {
            $(this).next().html(" ");
            $('.modal button[type=submit]').removeClass('hidden');
        }
        $('textarea[name=note]').next().html(noteErrorMessage);
        $('input[name=client_mobile]').next().html(mobileErrorMessage);  

    });

    var frm = $(".AutoFrm");
    frm.on('submit', function(e){
        e.preventDefault(); 
        $(".modal").modal('hide');
        var formData = new FormData($(this)[0]);
        ajax_request(formData);
    });

    $(document).ready(function() {
    $('#tokenModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget); // Button that triggered the modal
        var department_id = button.data('department-id'); // Extract info from data-* attributes
        var counter_id = button.data('counter-id');
        var user_id = button.data('user-id');
        var modal = $(this);
        modal.find('input[name="department_id"]').val(department_id);
        modal.find('input[name="counter_id"]').val(counter_id);
        modal.find('input[name="user_id"]').val(user_id);
    });

    $('#form-token').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Step 1: Get the form data and prepare for AJAX request
        var formData = new FormData(this);
        
        // Step 2: Send AJAX request to server
        $.ajax({
            url: '{{ url("admin/token/auto") }}',
            type: 'post',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,
            cache: false,
            processData: false,
            data:  formData,
            success: function(data)
            {
                if (data.status)
                {
                    // Step 3: Redirect to ticket page with ticket data
                    var ticketData = JSON.stringify(data.token);
                    var ticketUrl = "{{ url('ticket') }}?data=" + encodeURIComponent(ticketData);
                    window.location.href = ticketUrl;

                    // Step 4: Clear the form fields and hide the submit button
                    $("input[name=client_mobile]").val("");
                    $("textarea[name=note]").val("");
                    $('.modal button[type=submit]').addClass('hidden');
                }
            },
            error: function(xhr)
            {
                alert('wait...');
            }
        });
    });
});


    $("body #toggleScreen").on("click", function(){
        if ( $("body #cm-menu").is(":hidden") )
        {
            $("body #cm-menu").show();
            $("body #cm-header").show();
            $("body .cm-footer").removeClass('hide');
            $("body.cm-1-navbar #global").removeClass('p-0');
            $("body .container-fluid").removeClass('m-0 p-0');
            $("body .panel").removeClass('m-0');
            $("body #toggleScreenArea #screen-note").show();
            $("body .panel-heading h3").text("{{ trans('app.auto_token') }}");

            $("body #toggleScreenArea #screen-content").attr({'style': ''});
            $("body #toggleScreen").html('<i class="fa fa-arrows-alt"></i>');
        }
        else
        {
            $("body #cm-menu").hide();
            $("body #cm-header").hide();
            $("body .cm-footer").addClass('hide');
            $("body.cm-1-navbar #global").addClass('p-0');
            $("body .container-fluid").addClass('m-0 p-0');
            $("body .panel").addClass('m-0');
            $("body .panel-heading h3").text($('.cm-navbar>.cm-flex').text());

            $("body #toggleScreenArea #screen-note").hide(); 
            $("body #toggleScreenArea #screen-content").attr({'style': 'width:100%;text-align:center'});
            $("body #toggleScreen").html('<i class="fa fa-arrows"></i>');
        }
    });
 

    $('body').on("keydown", function (e) { 
        var key = e.key;
        var code = e.keyCode; 
  
        if ($('.modal.in').length == 0 && '{{$display->keyboard_mode}}'==1 && ((code >= 48 && code <=57) ||  (code >= 96 && code <=105) || (code >= 65 && code <=90)))
        {
            var keyList = '<?= $keyList; ?>';
            $.each(JSON.parse(keyList), function (id, obj) {
                if (obj.key == key) {
                    // check form and ajax submit
                    @if($display->show_note)
                        var modal = $('#tokenModal');
                        modal.modal('show');
                        modal.find('input[name=department_id]').val(obj.department_id);
                        modal.find('input[name=counter_id]').val(obj.counter_id);
                        modal.find('input[name=user_id]').val(obj.user_id);
                        modal.find("textarea[name=note]").val("");
                        modal.find('.modal button[type=submit]').addClass('hidden');
                    @else
                        var formData = new FormData();
                        formData.append("department_id", obj.department_id);
                        formData.append("counter_id", obj.counter_id);
                        formData.append("user_id", obj.user_id);
                        ajax_request(formData);
                        return false;
                    @endif
                }
            });
        }
    });
}(jQuery));
</script>
@endpush
 
 
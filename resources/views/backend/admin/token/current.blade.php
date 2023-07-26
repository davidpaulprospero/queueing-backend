@extends('layouts.backend')
@section('title', trans('app.todays_token'))

@section('content')
<div class="card ">
    <div class="card-header bg-danger text-white">
        <div class="row align-items-center">
            <div class="col">
                <h3>{{ trans('app.active') }} / {{ trans('app.todays_token') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body">
        <table id="myDataTable" class="datatable display table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('app.token_no') }}</th>
                    <th>Student ID</th>
                    <th>{{ trans('app.department') }}</th>
                    <th>{{ trans('app.counter') }}</th>
                    <th>Transaction Type</th>
                    <th>{{ trans('app.status') }}</th>
                    <th>{{ trans('app.created_at') }}</th>
                    <th width="120">{{ trans('app.action') }}</th>
                </tr>
            </thead> 
            <tbody>
                <!-- @if (!empty($tokens)) -->
                <!-- <?php $sl = 1 ?> -->
                    @foreach ($tokens->sortBy('created_at') as $token)
                        <tr>
                            
                            <td>{{ $sl++ }}</td>
                            <td>
                                {!! (!empty($token->is_vip)?("<span class=\"label label-danger\" title=\"VIP\">$token->token_no</span>"):$token->token_no) !!} 
                            </td>
                            <td>{{ !empty($token->studentId)?$token->studentId:null }}</td>
                            <td>{{ !empty($token->department)?$token->department->name:null }}</td>
                            <td>{{ !empty($token->counter)?$token->counter->name:null }}</td>
                            <td>{{ !empty($token->transactionType)?$token->transactionType->name:null }}</td>
                            <td>
                                @if($token->status==0) 
                                <span class="label label-primary">{{ trans('app.pending') }}</span> 
                                @elseif($token->status==1)   
                                <span class="label label-success">{{ trans('app.complete') }}</span>
                                @elseif($token->status==2) 
                                <span class="label label-danger">{{ trans('app.stop') }}</span>
                                @endif
                                {!! (!empty($token->is_vip)?('<span class="label label-danger" title="VIP">VIP</span>'):'') !!}
                            </td>
                            <td>
                                {{ (!empty($token->created_at)?date('j M Y h:i a',strtotime($token->created_at)):null) }}
                            </td>
                            <td>
                                <div class="btn-group"> 
                                    <a href="{{ url("admin/token/complete/$token->id") }}"  class="btn btn-success btn-sm" onclick="return confirm('Are you sure?')" title="Complete"><i class="fa fa-check"></i></a>
                                    <button type="button" data-toggle="modal" data-target=".transferModal" data-token-id='{{ $token->id }}' class="btn btn-primary btn-sm" title="Transfer"><i class="fa fa-exchange"></i></button> 

                                    <a href="{{ url("admin/token/stoped/$token->id") }}"  class="btn btn-warning btn-sm" onclick="return confirm('Are you sure?')" title="Stoped"><i class="fa fa-stop"></i></a>

                                    <button type="button" href='{{ url("admin/token/print") }}' data-token-id='{{ $token->id }}' class="tokenPrint btn btn-default btn-sm" title="Print" ><i class="fa fa-print"></i></button>

                                    <a href='{{ url("admin/token/delete/$token->id") }}'class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');" title="Delete"><i class="fa fa-times"></i></a>
                                </div>
                            </td>
                        </tr> 
                    @endforeach
                <!-- @endif -->
            </tbody>
        </table>
    </div> 
</div>  

<!-- Transfer Modal -->
<div class="modal fade transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel">
  <div class="modal-dialog" role="document">
    {{ Form::open(['url' => 'admin/token/transfer', 'class'=>'transferFrm']) }}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="transferModalLabel">{{ trans('app.transfer_a_token_to_another_counter') }}</h4>
      </div>
      <div class="modal-body"> 
        <div class="alert hide"></div>
        <input type="hidden" name="id">
        <p>
            <label for="department_id" class="control-label">{{ trans('app.department') }} </label><br/>
            {{ Form::select('department_id', $departments, null, ['placeholder' => 'Select Option', 'class'=>'select2', 'id'=>'department_id']) }}<br/>
        </p>

        <p>
            <label for="counter_id" class="control-label">{{ trans('app.counter') }} </label><br/>
            {{ Form::select('counter_id', $counters, null, ['placeholder' => 'Select Option', 'class'=>'select2', 'id'=>'counter_id']) }}
        </p> 

        <p>
            <label for="user_id" class="control-label">{{ trans('app.officer') }} </label><br/>
            {{ Form::select('user_id', $officers, null, ['placeholder' => 'Select Option', 'class'=>'select2', 'id'=>'user_id']) }}
        </p>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button class="button btn btn-success" type="submit"><span>{{ trans('app.transfer') }}</span></button>
      </div>
    </div>
    {{ Form::close() }}
  </div>
</div> 
@endsection

@push("scripts")
<script type="text/javascript">
(function() {
    if (window.addEventListener) {
        window.addEventListener("load", loadHandler, false);
    }
    else if (window.attachEvent) {
        window.attachEvent("onload", loadHandler);
    }
    else {
        window.onload = loadHandler;
    }

    function loadHandler() {
        setTimeout(doMyStuff, 2000);
    }

    function doMyStuff(response) {
        $.ajax({
            type: 'GET',
            url: '{{ URL::to("admin/token/current") }}',
            data: {},
            success: function(response) {
                var dataTable = $('#myDataTable').DataTable();

                // Clear the existing data in the table
                dataTable.clear();
                

                // Convert the token data into the required format
                var sl = 1;
                var tokenData = response.tokens.map(function(token, index) {
                    var transactionTypeName = token.transaction_type ? token.transaction_type.name : '';
                    var currentSl = index + 1;
                    var departmentName = token.department_id ? response.departments[token.department_id] : '';
                    var counterName = token.counter_id ? response.counters[token.counter_id] : '';
                    var statusHtml = token.status === 0 ? '<span class="label label-primary">' + '{{ trans('app.pending') }}' + '</span>' : '';
                    var tokenId = token.id;
                    var createdAtFormatted = new Date(token.created_at).toLocaleString();  // Format the created_at date

                    return [
                    currentSl,
                    token.token_no,
                    token.studentId,
                    departmentName,
                    counterName,
                    transactionTypeName,
                    statusHtml,
                    createdAtFormatted,
                    tokenId, // Store the token ID in the 8th column
                    hello,     // Add empty data for the 9th column
                    hi,     // Add empty data for the 10th column
                    // Add other columns as needed
                ];

                });

                // Add the new data to the table
                dataTable.rows.add(tokenData).draw();

                // Restore the button handlers for the 7th column
                dataTable.rows().every(function() {
                    var row = this;
                    var rowData = row.data();
                    var tokenId = rowData[8]; // Get the token ID from the 8th column

                    var buttonsHtml = '<div class="btn-group"> ' +
                        '<a href="{{ url("admin/token/complete/") }}/' + tokenId + '" class="btn btn-success btn-sm" onclick="return confirm(\'Are you sure?\')" title="Complete"><i class="fa fa-check"></i></a>' +
                        '<button type="button" data-toggle="modal" data-target=".transferModal" data-token-id="' + tokenId + '" class="btn btn-primary btn-sm" title="Transfer"><i class="fa fa-exchange"></i></button>' +
                        '<a href="{{ url("admin/token/stoped/") }}/' + tokenId + '" class="btn btn-warning btn-sm" onclick="return confirm(\'Are you sure?\')" title="Stoped"><i class="fa fa-stop"></i></a>' +
                        '<button type="button" href="{{ url("admin/token/print") }}" data-token-id="' + tokenId + '" class="tokenPrint btn btn-default btn-sm" title="Print"><i class="fa fa-print"></i></button>' +
                        '<a href="{{ url("admin/token/delete/") }}/' + tokenId + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\');" title="Delete"><i class="fa fa-times"></i></a>' +
                        '</div>';

                    // Replace the empty cell in the 7th column with the button HTML
                    $(row.node()).find('td:eq(8)').html(buttonsHtml);
                });

                dataTable.on('init.dt', function () {
                    $('select[name="myDataTable_length"]').removeClass('custom-select').css('width', 'auto');
                });
            }
        });
        loadHandler();
    }
  
    // modal open with token id
    $('.modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('input[name=id]').val(button.data('token-id'));
    }); 

    // transfer token
    $('body').on('submit', '.transferFrm', function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: 'json', 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,  
            // cache: false,  
            processData: false,
            data:  new FormData($(this)[0]),
            beforeSend: function() {
                $('.transferFrm').find('.alert')
                    .addClass('hide')
                    .html('');
            },
            success: function(data)
            {
                if (data.status)
                {  
                    $('.transferFrm').find('.alert')
                        .addClass('alert-success')
                        .removeClass('hide alert-danger')
                        .html(data.message);

                    setTimeout(() => { window.location.reload() }, 1500);
                }
                else
                {
                    $('.transferFrm').find('.alert')
                        .addClass('alert-danger')
                        .removeClass('hide alert-success')
                        .html(data.exception);
                }   
            },
            error: function(xhr)
            {
                alert('wait...');
            }
        });

    });

    // print token
    $("body").on("click", ".tokenPrint", function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type:'POST',
            dataType: 'json',
            data: {
                'id' : $(this).attr('data-token-id'),
                '_token':'<?php echo csrf_token() ?>'
            },
            success:function(data)
            {  
                var content = "<style type=\"text/css\">@media print {"+
                       "html, body {display:block;margin:0!important; padding:0 !important;overflow:hidden;display:table;}"+
                       ".receipt-token {width:100vw;height:100vw;text-align:center}"+
                       ".receipt-token h4{margin:0;padding:0;font-size:7vw;line-height:7vw;text-align:center}"+
                       ".receipt-token h1{margin:0;padding:0;font-size:15vw;line-height:20vw;text-align:center}"+
                       ".receipt-token ul{margin:0;padding:0;font-size:7vw;line-height:8vw;text-align:center;list-style:none;}"+
                       "}</style>";
                       
                content += "<div class=\"receipt-token\">";
                content += "<h4>{{ \Session::get('app.title') }}</h4>";
                content += "<h1>"+data.token_no+"</h1>";
                content +="<ul class=\"list-unstyled\">";
                content += "<li><strong>{{ trans('app.department') }} </strong>"+data.department+"</li>";
                content += "<li><strong>{{ trans('app.counter') }} </strong>"+data.counter+"</li>";
                content += "<li><strong>{{ trans('app.officer') }} </strong>"+data.firstname+' '+data.lastname+"</li>";
                if (data.note)
                {
                    content += "<li><strong>{{ trans('app.note') }} </strong>"+data.note+"</li>";
                }
                content += "<li><strong>{{ trans('app.date') }} </strong>"+data.created_at+"</li>";
                content += "</ul>";  
                content += "</div>";    
      
                // print 
                printThis(content);


            }, error:function(err){
                alert('failed!');
            }
        });  
    });
    
})();
</script>
@endpush
 
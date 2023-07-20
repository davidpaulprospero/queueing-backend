@extends('layouts.backend')
@section('title', trans('app.todays_token'))

@section('content')
<div class="card">
    <div class="card-header bg-danger text-white">
        <div class="row align-items-center">
            <div class="col">
                <h3>{{ trans('app.active') }} / {{ trans('app.todays_token') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body">
        <table id="myDataTable" class="datatable display table table-bordered" width="100%" cellspacing="0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <th width="120" class="exclude-print">{{ trans('app.action') }}</th>
        
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
                            <td class="exclude-print">
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
                                <button class="btn btn-primary take-button" data-token-id="{{ $token['id'] }}">Take</button>
                                    <a href="{{ url("officer/token/complete/$token->id") }}"  class="btn btn-success btn-sm" onclick="return confirm('Are you sure?')" title="Complete"><i class="fa fa-check"></i></a>
                                    @if($token->status==0)
                                    <a href="{{ url("officer/token/stoped/$token->id") }}"  class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to stop this token?')" title="Stop"><i class="fa fa-stop"></i></a>
                                    @endif

                                    <button type="button" href='{{ url("officer/token/print") }}' data-token-id='{{ $token->id }}' class="tokenPrint btn btn-default btn-sm" title="Print" ><i class="fa fa-print"></i></button>
                                </div>
                            </td>
                            
                        </tr> 
                    @endforeach
                <!-- @endif -->
            </tbody>
        </table>
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
        addEventListenersForTakeButtons();
    }

    function addEventListenersForTakeButtons() {
        const takeButtons = document.querySelectorAll('.take-button');
        console.log(takeButtons)
        // Add a click event listener to each "Take" button
        const baseApiUrl = '{{ URL::to("/") }}';
        takeButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the token ID from the data attribute of the button
                const tokenId = button.getAttribute('data-ticket-id');
                // Send an AJAX POST request to the API route
                fetch(`${baseApiUrl}/api/now-serving/${tokenId}/take`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: '{{ Auth::user()->id }}' // Replace this with the user ID as needed
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle the response data here (e.g., show a success message)
                    console.log(data);
                })
                .catch(error => {
                    // Handle errors (e.g., show an error message)
                    console.error(error);
                });
            });
        });
    }


    function doMyStuff(response) {
        $.ajax({
            type: 'GET',
            url: '{{ URL::to("officer/token/current") }}',
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
                    var createdAt = new Date(token.created_at);
                    var day = createdAt.getDate();
                    var month = createdAt.toLocaleString('default', { month: 'short' });
                    var year = createdAt.getFullYear();
                    var hour = createdAt.getHours().toString().padStart(2, '0');
                    var minute = createdAt.getMinutes().toString().padStart(2, '0');
                    var amPm = hour < 12 ? 'am' : 'pm';
                    hour = (hour % 12 || 12).toString().padStart(2, '0');

                    var createdAtFormatted = day + ' ' + month + ' ' + year + ' ' + hour + ':' + minute + ' ' + amPm;
                    // Format the created_at date

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
                        '<button class="btn btn-primary take-button" data-ticket-id="' + tokenId + '">Take</button>' +
                        '<a href="{{ url("officer/token/complete/") }}/' + tokenId + '" class="btn btn-success btn-sm" onclick="return confirm(\'Are you sure?\')" title="Complete"><i class="fa fa-check"></i></a>' +
                        '<button type="button" href="{{ url("officer/token/print") }}" data-token-id="' + tokenId + '" class="tokenPrint btn btn-default btn-sm" title="Print"><i class="fa fa-print"></i></button>' +
                        '</div>';

                         // Add the Stop button to the button HTML if the token status is pending (0)
                    if (rowData[6] === '<span class="label label-primary">Pending</span>') {
                        buttonsHtml += '<a href="{{ url("officer/token/stoped/") }}/' + tokenId + '" class="btn btn-warning btn-sm" onclick="return confirm(\'Are you sure you want to stop this token?\')" title="Stop"><i class="fa fa-stop"></i></a>';
                    }

                    buttonsHtml += '</div>';

                    // Replace the empty cell in the 7th column with the button HTML
                    $(row.node()).find('td:eq(8)').html(buttonsHtml);
                });
                addEventListenersForTakeButtons();
            }
        });
        loadHandler(); 
        
    }

})();
</script>
@endpush
 
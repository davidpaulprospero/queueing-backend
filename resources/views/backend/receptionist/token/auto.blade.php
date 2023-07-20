@extends('layouts.backend')
@section('title', trans('app.auto_token'))

@section('content')
<div class="panel panel-primary" id="document">
    <div class="panel-heading pt-0 pb-0">
        <ul class="row m-0 list-inline">
            <li class="col-xs-6 col-sm-4 p-0">
                <img src="{{ asset('public/assets/img/icons/logo.jpg') }}" width="210" height="50">
            </li>  
            <li class="col-xs-4 col-sm-4 hidden-xs" id="screen-title">
                <h3 class="mt-1 pt-1">{{ trans('app.auto_token') }}</h3>
            </li>         
        </ul>
    </div>   
    

<div class="panel-body" id="screen-content-container">
  <div class="row" id="screen-content" style="text-align: right;">
    @if($display->sms_alert || $display->show_note)
      <!-- With Mobile No -->
      @foreach ($departmentList as $department) 
      <div class="p-1 m-1 btn btn-primary capitalize text-center">
        <button 
          id="department-button-{{ $department->department_id }}" 
          type="button" 
          class="p-1 m-1 btn btn-primary capitalize text-center department-button"
          style="min-width: 15vw; white-space: pre-wrap; border-radius: 10px;"
          data-toggle="modal" 
          data-target="#tokenModal"
          data-department-id="{{ $department->department_id }}"
          data-counter-id="{{ $department->counter_id }}"
          data-user-id="{{ $department->user_id }}"
        >       
          <h5>{{ $department->name }}</h5>
          <h6>{{ $department->officer }}</h6>
        </button> 
      </div>
 
  </div>

             </button>  
                </div>
                @endforeach  
                <!--Ends of With Mobile No -->
            @else
            <script>
  // Get all the department buttons
  var buttons = document.getElementsByClassName('department-button');
  var container = document.getElementById('screen-content-container');

  // Add click event listener to each button
  for (var i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener('click', function() {
      container.style.display = 'none';
    });
  }
</script>

<style>
    .department-form {
        display: none;
        transition: opacity 0.3s ease-in-out;
    }

    .department-form.show {
        display: block;
        opacity: 1;
    }

    .department-form.hide {
        opacity: 0;
    }
</style>

<div class="row">
    @foreach ($departmentList as $department)
        <div class="col-md-6">
            <button 
                id="department-button-{{ $department->department_id }}" 
                class="p-1 m-1 btn btn-primary capitalize text-center rounded department-button"
                style="width: 100%; white-space: pre-wrap; border-radius: 50px;" 
                data-toggle="modal" data-target="#department-modal-{{ $department->department_id }}"
            >
                <h5>{{ $department->name }}</h5>
                <h6>{{ $department->officer }}</h6>
            </button>

            <div class="modal fade" id="department-modal-{{ $department->department_id }}" tabindex="-1" role="dialog" aria-labelledby="department-modal-{{ $department->department_id }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="department-modal-{{ $department->department_id }}-label">Department Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                            <div class="modal-body">
                            {{ Form::open(['url' => 'receptionist/token/auto', 'class' => 'AutoFrm p-1 m-1 capitalize text-center col-12']) }}

                            <div class="form-group">
                                <label for="studentId">Student ID</label>
                                <input type="text" name="studentId" class="form-control" placeholder="Enter Student ID" required>
                            </div>

                            <div class="form-group">
                                <label for="transaction_type_id">Transaction Type</label>
                                <select class="form-control" id="transaction_type_id" name="transaction_type_id">
                                    <option value="">{{ trans('app.select_transaction_type') }}</option>
                                    @foreach ($department->department->transactionTypes as $transactionType)
                                        <option value="{{ $transactionType->id }}">{{ $transactionType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="department_id" value="{{ $department->department_id }}">
                            <input type="hidden" name="counter_id" value="{{ $department->counter_id }}">
                            <input type="hidden" name="user_id" value="{{ $department->user_id }}">

                            <!-- <div id="transaction-list">
                                Transaction list will be dynamically added here -->
                         

                            <input type="submit" class="btn btn-primary submit-ticket-btn" value="Submit">
                            <!-- <button id="add-transaction-btn" class="btn btn-primary mt-2">Add Another Transaction</button> -->

                            
                            <div id="ticket" style="padding-top: 120px; display: flex; justify-content: flex-end;"></div>

                            {{ Form::close() }} 
                            <!-- <div class="download-button-container" style="display: none;"> -->
                            <button id="downloadBtn" class="btn btn-primary">Save Ticket</button> 
        
                </div>
                </div>
              </div>
                </div>
                </div>
                @endforeach
            </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                    <script>
    document.getElementById('downloadBtn').addEventListener('click', function() {
        // Generate the PDF content
        var pdfContent = document.getElementById('ticket');
        var ticketNumber = $('#downloadBtn').attr('data-ticket-number');

        // Set options for html2pdf
        var options = {
            filename: 'ticket_' + ticketNumber + '.pdf', // Set the filename dynamically
            image: { type: 'jpeg', quality: 0.2 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a6', orientation: 'portrait' }
        };

        // Generate PDF using html2pdf
        html2pdf().set(options).from(pdfContent).save();
    });

    // Hide the download button initially
    $('.download-button-container').hide();

    // Show the download button when a department form is submitted successfully
    $('form').on('submit', function() {
        $(this).find('.download-button-container').show();
    });
</script>


<script>
    // Add event listeners to the department buttons
    const departmentButtons = document.querySelectorAll('.department-button');
    departmentButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Hide all other department forms
            const allForms = document.querySelectorAll('.modal');
            allForms.forEach(form => {
                if (form !== button.nextElementSibling) {
                    form.style.display = 'none';
                }
            });

            // Toggle the visibility of the associated department form
            const form = button.nextElementSibling;
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>

                    </div>
            @endif  
            <script>
                // Disable the button after submitting the form
                const forms = document.querySelectorAll('.AutoFrm');
                forms.forEach((form) => {
                    form.addEventListener('submit', function(event) {
                        const button = event.target.querySelector('button');
                        button.disabled = true;
                    });
                });
            </script>
        </div>  
    </div> 
</div>  

<script type="text/javascript">
 $(document).ready(function() {
  var generateTicket = false;

  // Show tokenForm when button is clicked
  $('.department-button').on('click', function() {
    generateTicket = false; // Disallow ticket generation initially
    const form = $(this).next('.modal');
    form.find('input[name=studentId]').val(''); // Reset studentId field
    form.find('select[name=transaction_type_id]').val(''); // Reset transaction_type_id field

    // Calculate the position of the tokenForm relative to the button
    const buttonPosition = $(this).offset();
    const buttonHeight = $(this).outerHeight();
    const formHeight = form.outerHeight();
    const formPosition = {
      top: buttonPosition.top + buttonHeight + 10, // Adjust the value if needed
      left: buttonPosition.left
    };

    // Position and show the tokenForm
    form.css(formPosition).show();
  });

  // Form submission
  $('.AutoFrm').on('submit', function(e) {
    e.preventDefault();

    // Check if the required fields are filled
    const studentId = $(this).find('input[name=studentId]').val();
    const transactionType = $(this).find('select[name=transaction_type_id]').val();

    if (studentId && transactionType) {
      generateTicket = true; // Allow ticket generation
      const formData = new FormData($(this)[0]);
      // ... Rest of the code to process the form submission and generate the ticket
      ajax_request(formData, $(this));
    }

    // Hide tokenForm
    // $(this).closest('.modal').hide();
  });

  function ajax_request(formData) {
    $.ajax({
      url: '{{ url("receptionist/token/auto") }}',
      type: 'post',
      dataType: 'json',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      contentType: false,
      cache: false,
      processData: false,
      data:  formData,
      success: function(data) {
        if (data.status) {
          var content = "<div class=\"ticket-container\" style=\"position: sticky; top: 45px; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 200px; background: white; border: 1px solid black;\">";
          content += "<div class=\"receipt-token\" style=\"padding: 20px; text-align: center;\">";
          content += "<div style=\"display: flex; justify-content: center; align-items: center;\">";
          content += "<img src=\"{{ asset('public/assets/img/icons/lpu.png') }}\" width=\"20\" height=\"20\">";
          content += "<h4 style=\"font-size: 16px; margin: 2px 0;\">{{ \Session::get('app.title') }}</h4>";
          content += "</div>";
          content += "<h1 style=\"font-size: 36px; margin: 14px 0;\"><strong>" + data.token.token_no + "</h1></strong>"; // Adjusted font size
          content += "<hr style=\"border-top: 1px dashed black; margin: 4px 0;\">";
          content += "<ul class=\"list-unstyled\" style=\"font-size: 12px; margin: 0; padding: 0;\">"; // Adjusted font size
          content += "<li><strong>Student No.: </strong>" + data.token.studentId + "</li>";
          content += "<li><strong>{{ trans('app.department') }}: </strong>" + data.token.department + "</li>";
          content += "<li><strong>Transaction Type: </strong>" + data.transactionType.name + "</li>";
          content += "<li><strong>{{ trans('app.counter') }}: </strong>" + data.token.counter + "</li>";
          content += "<li><strong>{{ trans('app.date') }}: </strong>" + data.token.created_at + "</li>";
          content += "</ul>";
          content += "</div>";
          content += "</div>";

          // Display ticket content in the corresponding modal
          const modal = form.closest('.modal');
          modal.find('#ticket').html(content);
          modal.find('#downloadBtn').attr('data-ticket-number', data.token.token_no);

          $("input[name=client_mobile]").val("");
          $("textarea[name=note]").val("");
          modal.find('button[type=submit]').addClass('hidden');
        }
      },
    });
  }

  // Disable the button after submitting the form
  $('.AutoFrm').on('submit', function(event) {
    const button = $(this).find('button');
    button.prop('disabled', true);
  });

});
</script>

@endsection

@push("scripts")

<script type="text/javascript">
$(document).ready(function() {
  $('.AutoFrm').on('submit', function(e) {
    e.preventDefault();

    // Check if the required fields are filled
    var studentId = $(this).find('input[name=studentId]').val();
    var transactionType = $(this).find('select[name=transaction_type_id]').val();

    if (studentId && transactionType) {
      // Perform the AJAX request
      var formData = new FormData($(this)[0]);
      ajax_request(formData);
    }
  });

function ajax_request(formData) {
    $.ajax({
        url: '{{ url("receptionist/token/auto") }}',
        type: 'post',
        dataType: 'json',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        cache: false,
        processData: false,
        data:  formData,
        success: function(data) {
            if (data.status) {
                var content = "<div class=\"ticket-container\" style=\"position: sticky; top: 45px; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 200px; background: white; border: 1px solid black;\">";
content += "<div class=\"receipt-token\" style=\"padding: 10px; text-align: center;\">";
content += "<div style=\"display: flex; justify-content: center; align-items: center;\">";
content += "<img src=\"{{ asset('public/assets/img/icons/lpu.png') }}\" width=\"20\" height=\"20\">";
content += "<h4 style=\"font-size: 16px; margin: 2px 0;\">{{ \Session::get('app.title') }}</h4>";
content += "</div>";
content += "<h1 style=\"font-size: 36px; margin: 14px 0;\"><strong>" + data.token.token_no + "</h1></strong>"; // Adjusted font size
content += "<hr style=\"border-top: 1px dashed black; margin: 4px 0;\">";
content += "<ul class=\"list-unstyled\" style=\"font-size: 12px; margin: 0; padding: 0;\">"; // Adjusted font size
content += "<li><strong>Student No.: </strong>" + data.token.studentId + "</li>";
content += "<li><strong>{{ trans('app.department') }}: </strong>" + data.token.department + "</li>";
content += "<li><strong>Transaction Type: </strong>" + data.transactionType.name + "</li>";
content += "<li><strong>{{ trans('app.counter') }}: </strong>" + data.token.counter + "</li>";
content += "<li><strong>{{ trans('app.date') }}: </strong>" + data.token.created_at + "</li>";
content += "</ul>";
content += "</div>";
content += "</div>";

  // Display ticket content in #ticket div
  $('#ticket').html(content);
  $('#downloadBtn').attr('data-ticket-number', data.token.token_no);

  $("input[name=client_mobile]").val("");
  $("textarea[name=note]").val("");
  $('.modal button[type=submit]').addClass('hidden'); 
                
            }
        },
    });
}

    $(document).ready(function() {
        $("body #cm-menu").hide();
        $("body #cm-header").hide();
        $("body .cm-footer").addClass('hide');
        $("body.cm-1-navbar #global").addClass('p-0');
        $("body .container-fluid").addClass('m-0 p-0');
        $("body .panel").addClass('m-0');
        $("body .panel-heading h3").text($('.cm-navbar > .cm-flex').text());

        $("body #toggleScreenArea #screen-note").hide(); 
        $("body #toggleScreenArea #screen-content").attr({'style': 'width:100%;text-align:center'});
        $("body #toggleScreen").html('<i class="fa fa-arrows"></i>');
    });
});
</script>
@endpush
 
 
@extends('layouts.backend')
@section('title', trans('app.display_setting'))

@section('content')
<div class="card ">
    <div class="card-header bg-danger text-white">
        <div class="row align-items-center">
            <div class="col">
                <h3>{{ trans('Update Video Information') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body">
        <form id="updateForm" enctype="multipart/form-data" action="{{ route('updateVideo', $video->id) }}" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="title">{{ trans('Title') }}</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $video->title }}">
            </div>
            <div class="form-group">
                <label for="description">{{ trans('Description') }}</label>
                <textarea name="description" id="description" class="form-control">{{ $video->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('Update Video') }}</button>
        </form>
    </div>
</div>

@push("scripts")
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        // Update video
        $('#updateForm').submit(function(event) {
            event.preventDefault();

            // Disable submit button during update
            $('#updateForm button[type="submit"]').prop('disabled', true);

            // Create FormData object and append file and other form data
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route("updateVideo", $video->id) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Display success message
                    alert('Video updated successfully');

                    // Reset form and enable submit button
                    // $('#updateForm')[0].reset();
                },
                    // $('#updateForm button[type="submit"]').prop('disabled', false);
                error: function(xhr, status, error) {
                    // Display error message
                    var errorMessage = xhr.responseJSON.message;
                    alert(errorMessage);

                    // Enable submit button
                    $('#updateForm button[type="submit"]').prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
@endsection
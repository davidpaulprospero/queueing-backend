@extends('layouts.backend')
@section('title', trans('app.display_setting'))

@section('content')
<div class="card ">
    <div class="card-header bg-danger text-white">
        <div class="row align-items-center">
            <div class="col">
                <h3>{{ trans('Display Video Settings') }}</h3>
            </div> 
        </div>
    </div>

    <div class="panel-body">
        <form id="uploadForm" enctype="multipart/form-data" action="{{ route('uploadVideo') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="video">{{ trans('Upload Video') }}</label>
                <input type="file" name="video" id="video" class="form-control">
            </div>
            <div class="form-group">
                <label for="title">{{ trans('Title') }}</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">{{ trans('Description') }}</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('Upload Video') }}</button>
        </form>

        <!-- Video List -->
        <div id="videoList" class="mt-3">
            <h4>{{ trans('Video List') }}</h4>
            <ul id="sortable">
                @foreach ($videos as $video)
                    <li data-video-id="{{ $video->id }}">
                        <span>{{ $video->title }}</span>
                        <a href="{{ route('showUpdateForm', ['id' => $video->id]) }}" class="update-video" data-video-id="{{ $video->id }}">
                            Update
                        </a>
                        <a href="#" class="delete-video" data-video-id="{{ $video->id }}">
                            Delete
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@push("scripts")
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        // Delete video
        $('#videoList').on('click', '.delete-video', function(event) {
            event.preventDefault();
            var id = $(this).data('video-id');

            // Show confirmation dialog
            if (confirm('Are you sure you want to delete this video?')) {
                // Send AJAX request to delete the video
                $.ajax({
                    url: '{{ route("deleteVideo", ["id" => $video->id]) }}',
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Remove the video item from the list
                        $('[data-video-id="' + id + '"]').remove();
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON.message;
                        alert(errorMessage);
                    }
                });
            }
        });

        $('#uploadForm').submit(function(event) {
            event.preventDefault();

            // Disable submit button during upload
            $('#uploadForm button[type="submit"]').prop('disabled', true);

            // Create FormData object and append file and other form data
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route("uploadVideo") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Display success message
                    alert(response.message);
                    
                    // Reset form and enable submit button
                    $('#uploadForm')[0].reset();
                    $('#uploadForm button[type="submit"]').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    // Display error message
                    var errorMessage = xhr.responseJSON.message;
                    alert(errorMessage);

                    // Enable submit button
                    $('#uploadForm button[type="submit"]').prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
@endsection
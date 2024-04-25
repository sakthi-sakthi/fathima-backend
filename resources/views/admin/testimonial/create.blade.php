@extends('admin.layouts.master')

@section('title')
    {{ __('main.AddNewtestimonial') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.AddNewtestimonial') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.testimonial.index') }}">{{ __('main.testimonials') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('main.AddNewtestimonial') }}</li>
                        </ol>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.testimonial.store') }}" method="post" id="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id') }}">
                                    {{-- <input type="hidden" id="category_id" name="category_id"
                                        value="{{ old('category_id') }}"> --}}
                                    <div class="form-group">
                                        <label for="title">{{ __('main.Title') }}</label>
                                        <input type="text"
                                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}">
                                        @error('title') <small class="ml-auto text-danger">{{ __('main.titleError') }}</small> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">{{ __('main.Content') }}</label>
                                        <input type="text"
                                        hidden
                                            class="form-control form-control-sm @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" value="{{ old('slug') }}">
                                            @error('slug') <small class="ml-auto text-danger">{{ __('main.slugError') }}</small> @enderror
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control form-control-sm" rows="3" id="content"
                                            name="content">{{ old('content') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <label for="language">{{ __('main.Language') }}</label>
                                </div>
                                <div class="card-body">
                                    <select name="language" id="language" class="form-control form-control-sm">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->language }}" @if (old('language') == $language->language) selected @endif>
                                                {{ $language->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <label for="media_img">{{ __('main.Featured Image') }}</label>
                                </div>
                                <div class="card-body">
                                    <img src="" alt="" id="media_img" class="w-100">
                                </div>
                                <div class="card-footer">
                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary float-left"
                                        id="choose">{{ __('main.Choose Image') }}</a>
                                    <a href="javascript:void(0);" class="btn btn-xs btn-warning float-right"
                                        id="remove">{{ __('main.Remove Image') }}</a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <label>{{ __('main.Category') }}</label>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <select class="form-control form-control-sm" id="category" name="category_id">
                                            <option value="0"></option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>
                                                    {{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="save-card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="btn btn-success btn-sm float-right"
                                    id="submit">{{ __('main.Save') }}</a>
                                    <a href="{{ route('admin.testimonial.index') }}" class="btn btn-danger btn-sm float-right mr-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </div><!-- /.content -->
    </div>

    @include('admin.layouts.media')
@endsection

@section('script')
<script>
    var uploadedDocumentMap = {};

    // Generate the secure URL using secure_url function
    var secureUrl = "{{ secure_url(route('admin.media.storeMedia')) }}";

    // Configure Dropzone with the secure URL
    Dropzone.options.documentDropzone = {
        url: secureUrl,
        maxFilesize: 50, // MB
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            var error = response.error;
            var filesize = response.filesize;
            if (error) {
                $("#error-container").html('<div id="error-message" style="color: red;font-weight:700;">' + error + '</div>');
                $("#error-message").show();
                file.previewElement.remove();
            } else if (filesize >= 2) {
                $("#error-container").html('<div id="error-message" style="color: red;font-weight:700;"> Please reduce your file size before you upload it...! </div>');
                $("#error-message").show();
                file.previewElement.remove();
            } else {
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name;
            }
            setTimeout(function () {
                $("#error-message").hide();
            }, 10000);
        },
        removedfile: function (file) {
            file.previewElement.remove();
            var name = '';
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name;
            } else {
                name = uploadedDocumentMap[file.name];
            }
            $('form').find('input[name="document[]"][value="' + name + '"]').remove();
        },
    };
 
    $(document).ready(function() {
        $('#submitmedia').click(function(event) {
            event.preventDefault(); // Prevent default form submission
            
            var formData = new FormData($('#media-form')[0]); // Serialize form data
            
            $.ajax({
                url: $('#media-form').attr('action'), // URL to submit the form to
                type: 'POST',
                data: formData, // Form data
                processData: false, // Don't process the data
                contentType: false, // Don't set contentType
                success: function(response) {
                    if (Array.isArray(response)) {
        // Empty the .allmedia container
        $('.allmedia').empty();

        // Append each media item to the .allmedia container
        response.forEach(function(media) {
            var mediaHtml = '<div class="thumbnail">' +
                                '<a href="javascript:void(0);">' +
                                    '<img id="' + media.id + '" src="' + media.thumbUrl + '" data-url="' + media.url + '" alt="' + media.alt + '" data-title="' + media.name + '">' +
                                '</a>' +
                            '</div>';
            $('.allmedia').append(mediaHtml);
        });
    } else {
        console.error("Response medias is not an array:", response);
    }
                    $(".allmedia").css("display", "block");
                    $(".add-medias").css("display", "none");
                    var myDropzone = Dropzone.forElement("#document-dropzone");
                    if(myDropzone) {
                        myDropzone.removeAllFiles();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        });
    });


</script>
@endsection

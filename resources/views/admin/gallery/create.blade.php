@extends('admin.layouts.master')

@section('title')
{{ __('main.Upload') }}
@endsection

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-dark">{{ __('main.Upload') }}</h4>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">{{ __('main.Gallery') }}</a></li>
              <li class="breadcrumb-item active">{{ __('main.Upload') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{route('admin.gallery.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="title">{{ __('main.Title') }}</label>
                          <input type="text"
                              class="form-control form-control-sm @error('title') is-invalid @enderror"
                              id="title" name="title" value="{{ old('title') }}">
                          @error('title') <small class="ml-auto text-danger">{{ __('main.titleError') }}</small> @enderror
                    </div>
                      <div class="form-group">
                        <label for="title">{{ __('main.Category') }}</label>
                          <select class="form-control form-control-sm" id="category" name="category_id">
                              <option value="0"></option>
                              @foreach ($categories as $category)
                                  <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>
                                      {{ $category->title }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                    <div class="card-body">
                      <div class="form-group">
                        <label for="title" >Upload Images</label>
                        <div id="error-container"></div> <br>
                      <div class="needsclick dropzone" id="document-dropzone">
                      </div>
                    </div>  
                  </div>
                    <div class="card-footer">
                      <a href="{{ route('admin.gallery.index') }}" class="btn btn-danger">Cancel</a>
                        <button class="btn btn-success text-center px-5" type="submit" id="submit">{{ __('main.Upload') }}</button>
                        <label class="ml-5" style="color: red"><b>Maximum file size: 2MB</b></label>
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
</div>

@endsection

@section('script')
<script>
  var uploadedDocumentMap = {};
  var secureUrl =  "{{ secure_url(route('admin.gallery.storeMedia')) }}";

Dropzone.options.documentDropzone = {
  url: secureUrl, 
  maxFilesize: 50, 
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
    }else if (filesize >= 2) {
      $("#error-container").html('<div id="error-message" style="color: red;font-weight:700;"> Please reduce your file size before you upload it...! </div>');
     $("#error-message").show();
     file.previewElement.remove();
    } else {
    $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
    uploadedDocumentMap[file.name] = response.name;
    }
    
    setTimeout(function() {
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

</script>
@endsection

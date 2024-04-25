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
                <li class="breadcrumb-item"><a href="{{ route('admin.media.index') }}">{{ __('main.Media') }}</a></li>
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
                <form action="{{route('admin.media.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div id="error-container"></div> <br>
                        <div class="needsclick dropzone" id="document-dropzone">
                        </div>
                    </div>
                    <div class="card-footer">
                      <a href="{{ route('admin.media.index') }}" class="btn btn-danger">Cancel</a>
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

// Generate the secure URL using secure_url function
var secureUrl = "{{ secure_url(route('admin.media.storeMedia')) }}";

// Configure Dropzone with the secure URL
Dropzone.options.documentDropzone = {
  url: secureUrl,
  maxFilesize: 50, // MB
  addRemoveLinks: true,
  /*
  dictDefaultMessage: "Dosyaları yüklemek için buraya bırakın",
  dictFallbackMessage: "Tarayıcınız drag'n'drop dosya yüklemelerini desteklemiyor.",
  dictFallbackText: "Dosyalarınızı eski günlerdeki gibi yüklemek için lütfen aşağıdaki geri dönüş formunu kullanın.",
  dictInvalidFileType: "Bu türdeki dosyaları yükleyemezsiniz.",
  dictCancelUpload: "Yüklemeyi iptal et",
  dictCancelUploadConfirmation: "Bu yüklemeyi iptal etmek istediğinizden emin misiniz?",
  dictRemoveFile: "Dosyayı kaldır",
  dictMaxFilesExceeded: "Daha fazla dosya yükleyemezsiniz.",
  */
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

@extends('admin.layouts.master')

@section('title')
{{ __('main.Create Slide') }}
@endsection

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-dark">{{ __('main.Create Slide') }}</h4>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin.slide.index') }}">{{ __('main.Slides') }}</a></li>
              <li class="breadcrumb-item active">{{ __('main.Create Slide') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">

                </div>
                <div class="col-md-1"></div>
            </div>
            <form id="slideform" action="{{ route('admin.slide.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                              
                                <div class="form-group">
                                    <label for="title">{{__('main.Title')}}</label>
                                    <input type="text" class="form-control form-control-sm" id="title" name="title">
                                </div>
                                <div class="form-group">
                                    <label for="bg">{{__('main.Background')}}</label>
                                    <p>Dimensions:<span class="text-danger">800x600 px</span> Filesize : <span class="text-danger">2 MB</span></p>
                                        <input type="file" name="images" id="images" class="form-control form-control-sm">
                                        
                                     
                                </div>
                                {{-- <div class="form-group">
                                    <label for="content">{{__('main.Content')}}</label>
                                    <input type="text" class="form-control form-control-sm" id="message" name="content">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
           
            <div class="card" id="save-card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success btn-sm float-right">{{ __('main.Update') }}</button>
                    {{-- <a href="javascript:void(0);" class="btn btn-success btn-sm float-right" id="submitslide">{{ __('main.Update') }}</a> --}}
                    <a href="{{ route('admin.slide.index') }}" class="btn btn-danger btn-sm float-right mr-2">Cancel</a>
                </div>
            </div>
        </form>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  @include('admin.layouts.media')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
  <script>
   
   $.validator.addMethod('imageFileType', function(value, element) {
    var allowedExtensions = ["jpg", "jpeg", "png"];
    var extension = value.split('.').pop().toLowerCase();
    return $.inArray(extension, allowedExtensions) !== -1;
}, 'Please select a valid image file type (jpg, jpeg, png).');

function validateImageDimensions(input, dimensions) {
    var file = input.files[0];
    
    if (file) {
        var img = new Image();
        img.onload = function() {
          console.log(img.width, img.height);
          if (img.width > dimensions[0] || img.height > dimensions[1]) {
                var errorMessage = 'The image dimensions must be less than or equal to ' + dimensions[0] + 'x' + dimensions[1] + ' pixels.';
                $(input).siblings('.error').remove(); // Remove any existing error message
                $(input).after('<label class="error text-danger">' + errorMessage + '</label>'); // Append error message after the file input field
            } else {
                $(input).siblings('.error').remove(); // Remove any existing error message if dimensions are valid
            }
        };
        img.src = URL.createObjectURL(file);
    }
}

// Add event listener for file input change
$('#images').on('change', function() {
    
    var dimensions = [800, 600]; 
    // validateImageDimensions(this, dimensions);
});


    $("#slideform").validate({
            rules: {
              images: {
                        required: true,
                        imageFileType: true,
                        maxFileSize: 2 * 1024 * 1024,
                        // imageDimensions: [800, 600]
                      },
                      title: {
                        required: true,
                    },
                    // content: {
                    //     required: true
                    // }
          },
            messages: {
              images: {
                    required: "Please upload an image.",
                    imageFileType: "Please select a valid image file type (jpg, jpeg, png).",
                    maxFileSize: "The image file size must be less than or equal to 2MB.",
                    // imageDimensions: "The image dimensions must be less than or equal to 800x600 pixels."
                },
                title: {
                    required: "Please enter a title.",
                },
                // content: {
                //     required: "Please enter a content.",
                // }
            },
            errorPlacement: function(error, element) {
            
                error.addClass('text-danger');
                error.insertAfter(element);
            },
            submitHandler: function(form) {
            
                form.submit();
            },

        });
        

    
</script>
@endsection

@section('script')

@endsection

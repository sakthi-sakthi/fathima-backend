@extends('admin.layouts.master')

@section('title')
{{ __('main.Slides') }}
@endsection

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-dark">{{ __('main.Slides') }}</h4>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a></li>
              <li class="breadcrumb-item active">{{ __('main.Slides') }}</li>
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
                <div class="card-header">
                    <a href="{{ route('admin.slide.create') }}" class="btn btn-success btn-sm">{{ __('main.Add New') }}</a>
                </div>
            </div>
            <div class="row" id="maincontent">
              @foreach ($slides as $slide)
              <div class="col-md-3" data-id="{{ $slide->id }}">
                  <div class="card">
                      <img src="{{asset('slideimages/'.$slide->bg)}}" alt="" class="w-100">
                      <p class="text-center slidetag"><b>{{$slide->title}}</b></p>
                      <p class="text-center slidetag">{{$slide->content}}</p>
                      <div class="text-right">
                          <a href="{{ route('admin.slide.edit',$slide->id) }}" title="{{ __('main.Edit') }}" class="btn btn-primary btn-xs"><i class="fas fa-pencil-alt"></i></a>
                          <form id="delete_{{$slide->id}}" action="{{route('admin.slide.destroy',$slide->id)}}" method="post" class="d-inline">
                              @method('DELETE')
                              @csrf
                              <a href="javascript:void(0)" onclick="validate({{$slide->id}})" title="{{ __('main.Delete') }}" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i></a>
                          </form>
                      </div>
                  </div>
              </div>
              @endforeach
          </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

@endsection

@section('script')

<script>
  $(document).ready(function() {
        // Enable sorting for the maincontent container
        $("#maincontent").sortable({
            items: "> .col-md-3",
            cursor: 'move',
            opacity: 0.6,
            update: function(event, ui) {
                // Get the sorted items
                var sortedItems = $(this).sortable("toArray", { attribute: 'data-id' });

                // Send an AJAX request to update the order
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.slide.updateOrder') }}",
                    data: {
                        sortedItems: sortedItems,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    });
</script>
@endsection

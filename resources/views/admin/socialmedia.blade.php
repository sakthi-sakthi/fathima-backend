@extends('admin.layouts.master')

@section('title')
{{ __('main.socialmedia') }}
@endsection

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h4 class="m-0 text-dark">{{ __('main.socialmedia') }}</h4>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a></li>
                
              <li class="breadcrumb-item active">{{ __('main.socialmedia') }}</li>
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
            <div class="card">
                <form action="@if(Request::segment(3) == "Addmedia")
             {{ route('admin.socialStore') }}
            @else
            {{ route('admin.socialupdate') }}
            @endif" method="post" id="form" >
                    @csrf
                   
                    @foreach ($socialdata as $data)  @endforeach
                    <input type="hidden" name="language" value="tr">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="cell" class="col-md-3">{{ __('main.Title') }}</label>
                            <input type="text" class="form-control form-control-sm col-md-9" id="title" name="title" value="{{ $data->title ?? '' }}" >
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-3">{{ __('main.channelid') }}</label>
                            <input type="text" class="form-control form-control-sm col-md-9"  id="channelid" name="channelid" value="{{ $data->channelid ?? '' }}">
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-3">{{ __('main.apikey') }}</label>
                            <input type="text" class="form-control form-control-sm col-md-9" id="apikey" name="apikey" value="{{ $data->apikey ?? '' }}">
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-3">{{ __('main.mediaurl') }}</label>
                            <input type="text" class="form-control form-control-sm col-md-9" id="mediaurl" name="mediaurl" value="{{ $data->mediaurl ?? '' }}">
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-3">{{ __('main.counts') }}</label>
                            <input type="number" class="form-control form-control-sm col-md-9"id="counts" name="counts" value="{{ $data->counts ?? '' }}">
                            <input type="hidden" name="id" value="{{ $data->id ?? ''  }}" max="2">
                        </div>
                    </div>
                  
                </form>
                <div class="card" id="save-card">
                    <div class="card-body">
                        @if(Request::segment(3) == "Addmedia")
                        @if ($count == 0)
                        <a href="javascript:void(0);" class="btn btn-success btn-sm float-right" id="socialmediasubmit">{{ __('main.Save') }}</a>
                        @endif
                        @else
                        <a href="javascript:void(0);" class="btn btn-success btn-sm float-right" id="socialmediasubmit">{{ __('main.Save') }}</a>
                        @endif
                        
                        @if(Request::segment(3) == "Addmedia")
                        <a href="{{ route('admin.social.edit') }}" class="btn btn-danger btn-sm float-right mr-2">Edit</a>
                        @else
                        <a href="{{ route('admin.social.index') }}" class="btn btn-danger btn-sm float-right mr-2">Cancel</a>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
</div>
@endsection

@section('script')

@endsection

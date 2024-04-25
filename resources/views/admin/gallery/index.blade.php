@extends('admin.layouts.master')

@section('title')
{{ __('main.Gallery') }}
@endsection

@section('content')
<?php $data = $selected ?? ''?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-4">
            <h4 class="m-0 text-dark">{{ __('main.Gallery') }}</h4>
          </div><!-- /.col -->
          <div class="col-sm-4 form-group">
            <form method="post" action="{{ route('admin.dropdown.change') }}">
              @csrf
                <label for="">filter :  </label>
              <div class="form-group">
                <select class="form-control form-control-sm" id="category" name="data" onchange="this.form.submit()">
                    <option value="0"></option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if ($category->id == $data) selected @endif>
                            {{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
          </form>
          </div>
          <div class="col-sm-4 form-group d-flex">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ __('main.Home') }}</a></li>
              <li class="breadcrumb-item active">{{ __('main.Gallery') }}</li>
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
                <div class="card-body">
                    <div class="infinite-scroll">
                        <div class="scroll-content">
                        @foreach ($medias as $media)
                        @php
                            $id = $media->id;
                            $media = $media->path;
                        @endphp
                            @if ($id)
                            <div class="thumbnail">
                                <a href="{{ route('admin.gallery.show',$id) }}">
                                    <img id="{{ $id }}" src="{{asset($media) }}">
                                </a>
                            </div>
                            @endif
                        @endforeach
                        {{-- {{ $medias->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
              <a href="{{ route('admin.gallery.index') }}" class="btn btn-danger">Cancel</a>
                </div>
        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
</div>

@endsection

@section('script')

@endsection

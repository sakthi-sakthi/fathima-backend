@extends('admin.layouts.master')

@section('title')
    {{ __('main.editActivities') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.editActivities') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">{{ __('main.Activities') }}</a></li>
                        <li class="breadcrumb-item active">{{ $message->title }}</li>
                    </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.messages.update', $message->id) }}" method="post" id="form" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="title">{{ __('main.Title') }}</label>
                                        <input type="text"
                                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') ?? $message->title }}">
                                        @error('title') <small
                                            class="ml-auto text-danger">{{ __('main.titleError') }}</small> @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="desc">{{ __('main.Content') }}</label>
                                        <textarea class="form-control form-control-sm" rows="3" id="content"
                                            name="content">{{ old('content') ?? $message->content }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <label for="language">{{ __('main.Activitydate') }}</label>
                                </div>
                                <div class="card-body">
                                    <input type="date" name="activitydate" id="activitydate" class="form-control form-control-sm @error('activitydate') is-invalid @enderror" value="{{ $message->activitydate }}">
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <label>{{ __('main.Category') }}</label>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                    <select class="form-control form-control-sm" id="category" name="category_id">
                                        <option value="1" {{ $message->category_id == '1' ? 'selected' : '' }}>Priest Message</option>
                                        <option value="2" {{ $message->category_id == '2' ? 'selected' : '' }}>Golden Jubilee Message</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                    <div class="card-header">
                                        <label>{{ __('Upload File (or) Image') }} <span style="color: red">(optional)</span></label>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            @if($message->file)
                                                <p>Current file: <a href="{{ asset('uploads/' . $message->file) }}" target="_blank" class="text-primary" disable >{{ $message->file }}</a></p>
                                            @endif
                                            <input type="file" name="file" id="file" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="card" id="save-card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="btn btn-success btn-sm float-right"
                                    id="submit">{{ __('main.Update') }}</a>
                                    <a href="{{ route('admin.messages.index') }}" class="btn btn-danger btn-sm float-right mr-2">Cancel</a>
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

@endsection

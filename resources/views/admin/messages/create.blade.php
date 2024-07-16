@extends('admin.layouts.master')

@section('title')
    {{ __('main.addrActivities') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.addrActivities') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.messages.index') }}">{{ __('main.Activities') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('main.addrActivities') }}</li>
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
                <form action="{{ route('admin.messages.store') }}" method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id') }}">
                                    <!-- <input type="hidden" id="category_id" name="category_id"
                                        value="{{ old('category_id') }}"> -->
                                    <div class="form-group">
                                        <label for="title">{{ __('main.Title') }} <span style="color: red">*</span></label>
                                        <input type="text"
                                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}">
                                        @error('title') <small class="ml-auto text-danger">{{ __('main.titleError') }}</small> @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="slug">{{ __('main.Content') }} <span style="color: red">*</span></label>
                                        <textarea class="form-control form-control-sm" rows="3" id="content"
                                            name="content">{{ old('content') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <label for="date">{{ __('Date') }} <span style="color: red">*</span></label>
                                </div>
                                <div class="card-body">
                                    <input type="date" name="activitydate" id="activitydate" class="form-control form-control-sm @error('activitydate') is-invalid @enderror" value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <label>{{ __('main.Category') }} <span style="color: red">*</span></label>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <select class="form-control form-control-sm" id="category" name="category_id">
                                            <option value="1">Priest Message</option>
                                            <option value="2">Golden Jubilee Message</option>
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
                                       <input type="file" name="file" id="file" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="card" id="save-card">
                            <div class="card-body">
                                <a href="javascript:void(0);" class="btn btn-success btn-sm float-right"
                                    id="submit">{{ __('main.Save') }}</a>
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

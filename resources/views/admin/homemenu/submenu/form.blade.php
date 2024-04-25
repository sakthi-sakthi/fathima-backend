@extends('admin.layouts.master')

@section('title')
@if(Request::segment(3)=="create") {{ __('main.AddSubmenu') }} @else {{ __('main.EditSubmenu') }} @endif
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">@if(Request::segment(3)=="create") {{ __('main.AddSubmenu') }} @else {{ __('main.EditSubmenu') }} @endif </h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.submenu.index') }}">{{ __('main.submenu') }}</a></li>
                            <li class="breadcrumb-item active">@if(Request::segment(3)=="create") {{ __('main.AddSubmenu') }} @else {{ __('main.EditSubmenu') }} @endif</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                    <form action="@if(Request::segment(3)=="create") {{ route('admin.submenu.store') }} @else {{ route('admin.submenu.update',$editsubmenu->id ) }} @endif" method="post" id="form">
                        @if(Request::segment(3) !="create")
                          @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <label for="title" id="label">{{__('main.AddSubmenu')}}</label>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">{{ __('main.mainmenu') }}</label>
                                                <select class="form-control form-control-sm" id="parent_id" name="parent_id">
                                                    <option value="0"></option>
                                                    @foreach ($parentdata as $category)
                                                        <option value="{{ $category->id }}"
                                                        @if (Request::segment(3) !="create")
                                                            @if ($editsubmenu->parent_id  == $category->id) selected @endif  @endif>
                                                            {{ $category->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="title">{{ __('main.Url') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-sm @error('link') is-invalid @enderror"
                                                    id="link" name="link" value="{{ $editsubmenu->link ?? '' }}">
                                                @error('link') <small class="ml-auto text-danger">{{ __('main.linkError') }}</small> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">{{ __('main.Title') }}</label>
                                                
                                                <input type="text"
                                                    class="form-control form-control-sm @error('title') is-invalid @enderror"
                                                    id="title" name="title" value="{{ $editsubmenu->title ?? '' }}">
                                                @error('title') <small class="ml-auto text-danger">{{ __('main.titleError') }}</small> @enderror
                                            </div>
                                        </div>
                                    </div>  
                                    </div>
                                </div>
                            </div>
                            <div class="card" id="save-card">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm float-right"
                                        id="submitSubmenu">{{ __('main.Save') }}</a>
                                        <a href="{{ route('admin.submenu.index') }}" class="btn btn-danger btn-sm float-right mr-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
            </div><!-- /.container-fluid -->
        </div><!-- /.content -->
    </div>
   
    @endsection

@section('script')
  

@endsection

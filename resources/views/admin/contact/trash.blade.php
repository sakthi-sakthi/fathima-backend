@extends('admin.layouts.master')

@section('title')
    {{ __('main.Contact Request') }}
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.Recycle') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.contact.index') }}">{{ __('main.Contact Request') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('main.Recycle') }}</li>
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
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('main.Name') }}</th>
                                    <th>{{ __('main.Email') }}</th>
                                    <th>{{ __('main.Mobile') }}</th>
                                    <th>{{ __('main.Message') }}</th>
                                    <th>{{ __('main.Creation Date') }}</th>
                                    <th>{{ __('main.Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->mobile }}</td>
                                        <td>{{ $contact->message }}</td>
                                        <td>{{ $contact->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('admin.contact.recover', $contact->id) }}"
                                                title="{{ __('main.Recover') }}" class="btn btn-warning btn-xs"><i
                                                    class="fas fa-recycle"></i></a>
                                            <form id="delete_{{ $contact->id }}"
                                                action="{{ route('admin.contact.destroy', $contact->id) }}" method="post"
                                                class="d-inline">
                                                @method('DELETE')
                                                @csrf
                                                <a href="javascript:void(0)" onclick="validate({{ $contact->id }})"
                                                    title="{{ __('main.Destroy') }}" class="btn btn-danger btn-xs"><i
                                                        class="far fa-times-circle"></i></a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div><!-- /.content -->
    </div>
@endsection

@section('script')

@endsection

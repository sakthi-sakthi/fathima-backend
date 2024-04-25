@extends('admin.layouts.master') @section('title') {{ __('main.Dashboard') }} @endsection @section('content')

<div class="content-wrapper">
    <div class="container">
        <div class="row mt-3">
            <!-- User Card -->
            <!-- User Count Card -->
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                            <div class="text-right">
                                <h5 class="card-title">Users</h5>
                                <p class="card-text">Total Users:</p>
                                <strong>{{ $usercount }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-newspaper fa-3x"></i>

                            </div>
                            <div class="text-right">
                                <h5 class="card-title">Features</h5>
                                <p class="card-text">Total Features:</p>
                                <strong>{{ $updates }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Card (Replace with your data) -->
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-bar fa-3x"></i>
                                
                            </div>
                            <div class="text-right">
                                <h5 class="card-title">Services</h5>
                                <p class="card-text">Total Services:</p>
                                <strong>{{ $services }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Card (Replace with your data) -->
            <div class="col-md-3">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                {{-- <i class="fas fa-chart-bar fa-3x"></i> --}}
                                <i class="fas fa-address-book fa-3x"></i>

                            </div>
                            <div class="text-right">
                                <h5 class="card-title">Contact Request</h5>
                                <p class="card-text">Total count:</p>
                                <strong>{{ $contactrequest }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin.user.create') }}" class="btn btn-success btn-sm">{{ __('main.Add New') }}</a>
                        <a href="{{ route('admin.user.trash') }}" class="btn btn-warning btn-sm float-right"><i class="fas fa-trash-alt"></i>{{ __('main.Recycle') }}</a>
                    </div>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('main.Name') }}</th>
                                    <th>{{ __('main.E-mail') }}</th>
                                    <th>{{ __('main.Role') }}</th>
                                    <th>{{ __('main.Statu') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.edit',$user->id) }}" title="{{ __('main.Edit') }}" class="btn btn-primary btn-xs"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="{{ route('admin.user.delete',$user->id) }}" onclick="validate({{$user->id}})" title="{{ __('main.Delete') }}" class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection @section('script') @endsection

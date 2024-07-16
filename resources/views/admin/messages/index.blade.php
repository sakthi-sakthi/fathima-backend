@extends('admin.layouts.master')

@section('title')
    {{ __('All Messages') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('All Messages') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('All Messages') }}</li>
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
                        <a href="{{ route('admin.messages.create') }}"
                            class="btn btn-success btn-sm">{{ __('main.Add New') }}</a>
                    </div>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>{{ __('main.Title') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('main.Creation Date') }}</th>
                                    <th>{{ __('main.Statu') }}</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $message)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $message->title }}</td>
                                        <td>
                                            @switch($message->category_id)
                                                @case(1)
                                                    Priest Message
                                                    @break

                                                @case(2)
                                                    Golden Jubilee Message
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $message->activitydate }}</td>
                                        <td>{{ $message->created_at->diffForHumans() }}</td>
                                        <td><input class="switch" type="checkbox" name="my-checkbox"
                                                data-id="{{ $message->id }}" @if ($message->status == 1) checked @endif
                                                data-toggle="toggle" data-size="mini"
                                                data-on="{{ __('main.Published') }}" data-off="{{ __('main.Draft') }}"
                                                data-onstyle="success" data-offstyle="danger"></td>

                                        <td>
                                            <a href="{{ route('admin.messages.show', $message->id) }}"
                                                class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('admin.messages.edit', $message->id) }}"
                                                class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirmDelete({{ $message->id }});">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <script>
                                                function confirmDelete(id) {
                                                    var confirmation = confirm("Do you really want to delete this message?");
                                                    if (confirmation) {
                                                        document.getElementById('delete-form-' + id).submit();
                                                    }
                                                }
                                            </script>
                                            <form id="delete-form-{{ $message->id }}" action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
@endsection

@section('script')
<script>
    $('.switch').change(function() {
        id = $(this).attr('data-id');
        status = $(this).prop('checked');
        $.get("{{ secure_url(route('admin.messages.dataswitch')) }}", {
            id: id,
            status: status
        });
    });
</script>

@endsection

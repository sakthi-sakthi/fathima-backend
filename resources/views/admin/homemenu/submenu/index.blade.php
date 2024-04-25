@extends('admin.layouts.master')

@section('title')
    {{ __('main.submenu') }}
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.submenu') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('main.submenu') }}</li>
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
                        <a href="{{ route('admin.submenu.create') }}"
                            class="btn btn-success btn-sm">{{ __('main.Add New') }}</a>
                        <a href="{{ route('admin.submenu.trashed') }}" class="btn btn-warning btn-sm float-right"><i
                                class="fas fa-trash-alt"></i>{{ __('main.Recycle') }}</a>
                    </div>
                    <div class="card-body">
                        
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('main.mainmenu') }}</th>
                                    <th>{{ __('main.Title') }}</th>
                                    <th>{{ __('main.Url') }}</th>
                                    <th>{{ __('main.Creation Date') }}</th>
                                    <th>{{ __('main.Statu') }}</th>
                                    {{-- <th></th> --}}
                                </tr>
                            </thead>
                            <tbody id="subcontent">
                                @foreach ($articles as $article)
                                    <tr class="row1" data-id="{{ $article->id }}" data-row-id={{ $article->Position }} >
                                        <td>{{ $article->mainmenuname }}</td>
                                        <td>{{ $article->title }}</td>
                                        <td>{{ $article->link }}</td>
                                        <td>{{ $article->created_at->diffForHumans() }}</td>
                                        {{-- <td><input class="switch" type="checkbox" name="my-checkbox"
                                                data-id="{{ $article->id }}" @if ($article->status == 1) checked @endif
                                                data-toggle="toggle" data-size="mini"
                                                data-on="{{ __('main.Published') }}" data-off="{{ __('main.Draft') }}"
                                                data-onstyle="success" data-offstyle="danger"></td>--}}
                                        <td>  
                                            <a href="{{ route('admin.submenu.edit', $article->id) }}"
                                                title="{{ __('main.Edit') }}" class="btn btn-primary btn-xs"><i
                                                    class="fas fa-pencil-alt"></i></a>
                                            <a href="{{ route('admin.submenu.delete', $article->id) }}"
                                                onclick="validate({{ $article->id }})" title="{{ __('main.Delete') }}"
                                                class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i></a>
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


    <script>
        $('.switch').change(function() {
        id = $(this).attr('data-id');
        status = $(this).prop('checked');
        $.get("{{ secure_url(route('admin.submenu.switch')) }}", {
            id: id,
            status: status
        });
    });
  
$( "tbody#subcontent" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        update: function() {
            updatePostOrder('row1');
        }
    });
function updatePostOrder(row){
    var Position = [];
     var token = $('meta[name="csrf-token"]').attr('content');
            $('tr.'+row).each(function(index, element) {
            Position.push({
                id: $(this).attr('data-id'),
                Position: index+1
            });
        });
        $.post("{{ secure_url(route('admin.submenu.updateorder')) }}", {
         Position: Position,
         _token: token
    })
    .done(function(response) {
        console.log(response);  
    })
    .fail(function(error) {
        console.error(error); 
        
    });
        
}

$(document).ready(function() {
        // Sort the rows based on the data-row-id attribute in ascending order
        var rows = $('#subcontent tr').get();
        rows.sort(function(a, b) {
            var keyA = parseInt($(a).data('row-id'));
            var keyB = parseInt($(b).data('row-id'));
            return keyA - keyB;
        });
        $.each(rows, function(index, row) {
            $('#subcontent').append(row);
        });
    });
    </script>
@endsection

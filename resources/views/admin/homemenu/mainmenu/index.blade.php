@extends('admin.layouts.master')

@section('title')
    {{ __('main.mainmenu') }}
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.mainmenu') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('main.mainmenu') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                    <form action="{{ route('admin.mainmenu.store') }}" method="post" id="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <label for="title" id="label">Add Mainmenu</label>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">{{ __('main.Title') }}</label>
                                                <input type="text" hidden name="type" value="store" id="typedata">
                                                <input type="text" hidden name="id" value="" id="id" hidden>
                                                <input type="text"
                                                    class="form-control form-control-sm @error('title') is-invalid @enderror"
                                                    id="title" name="title" value="{{ old('title') }}">
                                                @error('title') <small class="ml-auto text-danger">{{ __('main.titleError') }}</small> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">{{ __('main.Url') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-sm @error('link') is-invalid @enderror"
                                                    id="link" name="link" value="{{ old('link') }}">
                                                @error('link') <small class="ml-auto text-danger">{{ __('main.linkError') }}</small> @enderror
                                            </div>
                                        </div>
                                    </div>  
                                    </div>
                                </div>
                            </div>
                            <div class="card" id="save-card">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm float-right"
                                        id="submitmainmenu">{{ __('main.Save') }}</a>
                                        <a href="{{ route('admin.mainmenu.index') }}" class="btn btn-danger btn-sm float-right mr-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                <div class="card">
                    <div class="card-header">
                        
                        <a href="{{ route('admin.mainmenu.trashed') }}" class="btn btn-warning btn-sm float-right"><i
                                class="fas fa-trash-alt"></i>{{ __('main.Recycle') }}</a>
                    </div>
                    <div class="card-body">
                        
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('main.Title') }}</th>
                                    <th>{{ __('main.Url') }}</th>
                                    <th>{{ __('main.Creation Date') }}</th>
                                    <th>{{ __('main.Statu') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="maincontent">
                                @foreach ($articles as $article)
                                    <tr class="row1" data-id="{{ $article->id }}" data-row-id={{ $article->Position }} >
                                        <td>{{ $article->title }}</td>
                                        <td>{{ $article->link }}</td>
                                        <td>{{ $article->created_at->diffForHumans() }}</td>
                                        <td><input class="switch" type="checkbox" name="my-checkbox"
                                                data-id="{{ $article->id }}" @if ($article->status == 1) checked @endif
                                                data-toggle="toggle" data-size="mini"
                                                data-on="{{ __('main.Published') }}" data-off="{{ __('main.Draft') }}"
                                                data-onstyle="success" data-offstyle="danger"></td>
                                        <td> 
                                            <button
                                              id="editmainmenu"  data-id="{{ $article->id }}" title="{{ __('main.Edit') }}" class="btn btn-primary btn-xs editmainmenu"><i
                                                    class="fas fa-pencil-alt"></i></button>
                                            <a href="{{ route('admin.mainmenu.delete', $article->id) }}"
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
        $.get("{{ secure_url(route('admin.mainmenu.statusupdate')) }}", {
            id: id,
            status: status
        });
    });

    $('.editmainmenu').click(function() {
    var id = $(this).data('id'); 
    $.get("{{ secure_url(route('admin.mainmenu.editmain')) }}", {
        id: id,
    })
    .done(function(response) {
        console.log(); 
        var link = response.data.link;
        var title = response.data.title;
        $('#link').val(link);    $('#label').text('Edit Mainmenu'); 
        $('#title').val(title); $('#submitmainmenu').text('Update'); 
        $('#typedata').val('update'); $('#id').val(response.data.id)
    })
    .fail(function(error) {
        console.error(error); 
        
    });
});
$( "tbody#maincontent" ).sortable({
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
        $.post("{{ secure_url(route('admin.mainmenu.updateorder')) }}", {
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
        var rows = $('#maincontent tr').get();
        rows.sort(function(a, b) {
            var keyA = parseInt($(a).data('row-id'));
            var keyB = parseInt($(b).data('row-id'));
            return keyA - keyB;
        });
        $.each(rows, function(index, row) {
            $('#maincontent').append(row);
        });
    });
    </script>
@endsection


    <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>
    <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <!--Anything you want-->
    </div>
    <!-- Default to the left -->
    <center>
    <strong>{{ __('main.Copyright') }} &copy; {{ date('Y') }} <a href="/admin/home">Welcome To Benilde Resource Centre  </a>.</strong> {{ __('main.All rights reserved.') }}
</center>
    </footer>
</div>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



<script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
<script src="{{asset('admin')}}/plugins/jquery/jquery.min.js"></script>
<script src="{{asset('admin')}}/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="{{asset('admin')}}/dist/js/jscroll.min.js"></script>
<script src="{{asset('admin')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('admin')}}/dist/js/adminlte.min.js"></script>
<script src="{{asset('admin')}}/dist/js/script.js"></script>
<script src="{{asset('admin')}}/plugins/toastr/toastr.min.js"></script>
<script src="{{asset('admin')}}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="{{asset('admin')}}/plugins/summernote/summernote-bs4.min.js"></script>
<script src="{{asset('admin')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('admin')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('admin')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('admin')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{asset('admin')}}/plugins/dropzone/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/mode/javascript/javascript.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@if (session('message'))
    @if(session('type') == 'info')
        <script>
            toastr.info('{{ session("message") }} ');
        </script>
    @endif
    @if(session('type') == 'success')
        <script>
            toastr.success('{{ session("message") }} ');
        </script>
    @endif
    @if(session('type') == 'warning')
        <script>
            toastr.warning('{{ session("message") }} ');
        </script>
    @endif
    @if(session('type') == 'error')
        <script>
            toastr.error('{{ session("message") }} ');
        </script>
    @endif
@endif

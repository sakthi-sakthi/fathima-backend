<style>
  .dropdown-item{
    background-color:#012c6d;
  }
  .dropdown-item:focus, .dropdown-item:hover{
    background-color: #ff6f61;
  }
</style>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color: #012c6d;"></i></a>
      </li>
      <li class="nav-item dropdown d-none d-sm-inline-block" style="color: rgb(17 38 134);">
        <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item dropdown-toggle">{{ __('main.Add New') }}</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            <li><a href="{{route('admin.page.create')}}" class="dropdown-item">{{ __('main.Page') }}</a></li>
            <li><a href="{{route('admin.ourfeature.create')}}" class="dropdown-item">{{ __('main.ourfeature') }}</a></li>
            <li><a href="{{route('admin.media.create')}}" class="dropdown-item">{{ __('main.Media') }}</a></li>
            <li><a href="{{route('admin.user.create')}}" class="dropdown-item">{{ __('main.User') }}</a></li>
        </ul>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link mt-2"  data-toggle="dropdown" href="#">
          <i class="far fa-bell" style="color: #bf0a30;"></i>
          <span class="badge badge-warning navbar-badge">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">{{ __("main.No Notification") }}</span>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><img src="{{asset('admin')}}/img/mypic.png" style="width: 30px;" class="img-circle elevation-2" alt="User Image"></a>
      </li>
    </ul>
  </nav>

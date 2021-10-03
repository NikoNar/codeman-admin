

@include('admin-panel::layouts.header')


  <!-- =============================================== -->

@include('admin-panel::layouts.left-menu')


  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header col-md-4 pull-right no-padding-right"></section>
    <div class="clearfix"></div>
    <!-- Main content -->
    <section class="content">
    @yield('content')
    </section>

    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
@include('admin-panel::layouts.footer')





@include('admin-panel::layouts.header')


  <!-- =============================================== -->

@include('admin-panel::layouts.left-menu')


  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header col-md-4 pull-right no-padding-right">
      {{-- <h1>
        Pages
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Pages</a></li>
        <li class="active">Create New Page</li>
      </ol> --}}
      @include('admin-panel::messages.messages')
    </section>
    <div class="clearfix"></div>
    <!-- Main content -->
    <section class="content">
      {{-- <div class="callout callout-info">
        <h4>Tip!</h4>

        <p>Add the sidebar-collapse class to the body tag to get this layout. You should combine this option with a
          fixed layout if you have a long sidebar. Doing that will prevent your page content from getting stretched
          vertically.</p>
      </div> --}}
      <!-- Default box -->
      
      <!-- /.box -->
    @yield('content')
    </section>

    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
@include('admin-panel::layouts.footer')



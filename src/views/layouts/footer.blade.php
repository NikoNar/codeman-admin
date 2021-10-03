  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.2.0
    </div>
    @if(date('Y') > 2017)
    	<span>Copyright &copy; 2017 - {{ date('Y') }} <a href="//codeman.am" style="letter-spacing: 1px;">CODEMAN</a></span>
    
    @else
    	<span>Copyright &copy; {{ date('Y') }} <a href="//codeman.am" style="letter-spacing: 1px;">Codeman</a></span>
    @endif
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('admin-panel/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('admin-panel/js/jquery-ui.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('admin-panel/js/popper.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- SlimScroll -->
<script src="{{ asset('admin-panel/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- Jscroll -->
<script src="{{ asset('admin-panel/plugins/jscroll/jquery.jscroll.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('admin-panel/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- PACE -->
<script src="{{ asset('admin-panel/bower_components/PACE/pace.min.js') }}"></script>
<!-- SlimScroll -->
<!-- AdminLTE App -->
<script src="{{ asset('admin-panel/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin-panel/dist/js/demo.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- Query string -->
{{-- <script src="{{ asset('admin-panel/query-string/index.js') }}"></script> --}}
<script src="{{ asset('admin-panel/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin-panel/plugins/bootstrap-iconpicker-1.10.0/dist/js/bootstrap-iconpicker.bundle.min.js') }}"></script>

 
<script type="text/javascript" src="{{ asset('admin-panel/js/datatables/datatables.min.js') }}"></script>

{{-- <script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>


<script type="text/javascript" src="{{asset('admin-panel/bower_components/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('admin-panel/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>

<script src="{{ asset('admin-panel/js/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('admin-panel/js/admin.js') }}"></script>
<script src="{{ asset('admin-panel/js/colors.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $(function () {
        $('.datetimepicker-simple').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
        });
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
    
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
@include('admin-panel::messages.messages')
@yield('script')
@yield('after-script')
</body>
</html>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.1.3
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
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>


<!-- Bootstrap 3.3.7 -->
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

  <script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<script src="{{ asset('admin-panel/js/admin.js') }}"></script>
  <script src="{{ asset('admin-panel/js/colors.js') }}"></script>
<script>
  $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
</script>

@yield('script')
@yield('after-script')
</body>
</html>
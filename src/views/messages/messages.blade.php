@if (Session::has('success'))
	<script type="text/javascript">
		toastr.success('{!! Session::get('success') !!}', "Success");
	</script>
@endif

@if (isset($success))
	<script type="text/javascript">
		toastr.success('{!! $success !!}', "Success");
	</script>
@endif

@if (Session::has('warning'))
	<script type="text/javascript">
		toastr.warning('{!! Session::get('warning') !!}', "Warning");
	</script>
@endif

@if (Session::has('error'))
	<script type="text/javascript">
		toastr.error('{!! Session::get('error') !!}', "Error");
	</script>
@endif

@if(isset($errors) && !empty($errors->all()))
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
        	toastr.error('{!! $error !!}', "Error");
        </script>
    @endforeach
@endif
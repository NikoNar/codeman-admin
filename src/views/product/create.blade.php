@extends('admin-panel::layouts.app')
@section('style')
	
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">

	<!-- daterange picker -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/iCheck/all.css') }}">

	<!-- Bootstrap Color Picker -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">

  	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/timepicker/bootstrap-timepicker.min.css') }}">

@endsection
@section('content')
	<div class="box">
	    <div class="box-header with-border">
	        <h3 class="box-title">Create Product</h3>
	    </div>
	    <div class="box-body">
	        @include('admin-panel::product.parts.forms._create_edit_form')
	    </div>
	    <!-- /.box-body -->
	</div>
@endsection
@section('script')

	
	<script src="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

	<script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	
	<!-- date-range-picker -->
	<script src="{{ asset('admin-panel/bower_components/moment/min/moment.min.js') }} "></script>
	
	<script src="{{ asset('admin-panel/bower_components/bootstrap-daterangepicker/daterangepicker.js') }} "></script>
	<!-- bootstrap datepicker -->
	<script src="{{ asset('admin-panel/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }} "></script>
	<!-- bootstrap color picker -->
	<script src="{{ asset('admin-panel/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
	<!-- bootstrap time picker -->
	<script src="{{ asset('admin-panel/plugins/timepicker/bootstrap-timepicker.min.js') }} "></script>
	
	<!-- iCheck 1.0.1 -->
	<script src="{{ asset('admin-panel/plugins/iCheck/icheck.min.js') }}"></script>

	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\ProductRequest') !!}

	<script>
	  	$(function () {
	    	CKEDITOR.replace('editor');
	    	
	    	// var image_tag="<img src='http://gaiff-laravel.fx/images/media/full_size/c10-large.jpg'>";
	    	// var doctarget = opener.CKEDITOR.instances.editable;
	    	// doctarget.insertHtml(image_tag);
	  	})
	  	$('#datepicker').datepicker({
	  		format: "mm/dd/yyyy", // Notice the Extra space at the beginning

	  	}).datepicker('setDate', new Date());
	  	//Timepicker
	  	$('#timepicker').timepicker({
      		showInputs: false
	  	})

	</script>
@endsection()
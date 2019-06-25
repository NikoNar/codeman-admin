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
	        <h3 class="box-title">Add  Team Member</h3>
	    </div>
	    <div class="box-body">
	        @include('admin-panel::lecturer.parts.forms._create_edit_form')
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

	 {!! JsValidator::formRequest('Codeman\Admin\Http\Requests\lecturerRequest') !!} 

	<script>
	  	$(function () {
	    // 	if($('#content').length > 0){
	    // 		CKEDITOR.replace('content');	  			
	  		// }
	    	
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

	  // 	@if(isset($page))
			// builderOptions = {!! $page->description !!};
			// if(Object.keys(builderOptions).length === 0 && builderOptions.constructor === Object){
   //      		builderOptions = [];
			// }
   //      @else
   //      	builderOptions = [];
   //      @endif
		// builderOptions = JSON.parse(builderOptions);
	  	// $(function () {
	    	// if($('textarea').is('editor')){
	    		// CKEDITOR.replace('editor');	  			
	  		// }
	    	// if($('textarea').is('editor-2')){
	    	// 	CKEDITOR.replace('editor-2');
	    	// }
	  	// })

	  	
	  	// $('body').off('submit', '#lecturer-store').on('submit', '#lecturer-store', function(e){
	  	// 	e.preventDefault();
	  	// 	var form = $(this);
	  	// 	var form_data = form.serializeArray();
	  	// 	form_data.push({name: 'description', 'value': JSON.stringify(builderOptions)}); 
	  	// 	console.log(form_data);
	  	// 	$.ajax({
	  	// 	    type: 'POST',
	  	// 	    url: form.attr('action'),
	  	// 	    dataType: 'JSON',
	  	// 	    // data: {'content' '_token' : $('meta[name="csrf-token"]').attr('content')},
	  	// 	    data: form_data,
	  	// 	    success: function(data){
	  	// 	    	console.log(data); 
    //                 window.location = "/admin/lecturer/edit/" + data.lecturer_id
	  	// 	    }, 
	  	// 	    error:function() {
	  	// 	    	console.log('er');
	  	// 	    }
	  	// 	});
	  	// });
	  </script>
	  <!-- <script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script> -->

	</script>
@endsection()
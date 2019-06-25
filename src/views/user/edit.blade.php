@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
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
	        <h3 class="box-title">Edit user</h3>
	       
	        <a href="{{ route('user.create') }}" class="btn btn-primary btn-flat pull-right ">Add New</a>
	        @if(isset($parent_lang_id) || isset($user) && $user->lang == 'arm')
	        	@if(isset($parent_lang_id))
	        		<a href="{{ route('user.edit', $parent_lang_id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
	        	@else 
	        		<a href="{{ route('user.edit', $user->parent_lang_id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
	        	@endif
	        @else
	        	
	        @endif
	    </div>
	    <div class="box-body">
	        @include('admin-panel::user.parts.forms._create_edit_form')
	    </div>
	    <!-- /.box-body -->
	</div>
@endsection
@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
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

	{{-- {!! JsValidator::formRequest('App\Http\Requests\Admin\PageRequest') !!} --}}
	<script>
		$(document).ready(function(){
			$('#generate-password').on('click', function(e){
				String.prototype.pick = function(min, max) {
				    var n, chars = '';

				    if (typeof max === 'undefined') {
				        n = min;
				    } else {
				        n = min + Math.floor(Math.random() * (max - min + 1));
				    }

				    for (var i = 0; i < n; i++) {
				        chars += this.charAt(Math.floor(Math.random() * this.length));
				    }

				    return chars;
				};


				String.prototype.shuffle = function() {
				    var array = this.split('');
				    var tmp, current, top = array.length;

				    if (top) while (--top) {
				        current = Math.floor(Math.random() * (top + 1));
				        tmp = array[current];
				        array[current] = array[top];
				        array[top] = tmp;
				    }

				    return array.join('');
				};
				var specials = '!@#$%&*-()_+{}:<>';
				var lowercase = 'abcdefghijklmnopqrstuvwxyz';
				var uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				var numbers = '0123456789';

				var all = specials + lowercase + uppercase + numbers;

				var password = '';
				password += specials.pick(1);
				password += lowercase.pick(1);
				password += uppercase.pick(1);
				password += all.pick(5, 10);
				password = password.shuffle()

				$('input[name="password"]').val(password);
			});
		});  	
	</script>
	  

	</script>
@endsection()
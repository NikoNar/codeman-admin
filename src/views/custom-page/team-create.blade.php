
@extends('admin.layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="col-md-8 col-md-offset-2">
		<div class="box ">
		    <div class="box-header with-border">
		    	@if(isset($member))
		        	<h3 class="box-title">Edit Team Member</h3>
		        	<a href="{{ action("Admin\CustomPagesController@teamCreate") }}" class="btn btn-primary btn-flat pull-right ">Add New</a>
		        	@if(isset($parent_lang_id) || isset($member) && $member->lang == 'arm')
		        		@if(isset($parent_lang_id))
		        			<a href="{{ action("Admin\CustomPagesController@teamEdit, [$parent_lang_id]") }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
		        		@else
		        			<a href="{{ action("Admin\CustomPagesController@teamEdit", [$member->parent_lang_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
		        		@endif
		        	@else
		        		<a href="{{ action("Admin\CustomPagesController@teamTranslate", [$member->id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to Armenian</a>
		        	@endif
		    	@else
			    	@if(isset($parent_lang_id))
			        	<h3 class="box-title" style="line-height: 34px;">Translate "{{ $name }}" Member</h3>
			    	@else
			        	<h3 class="box-title" style="line-height: 34px;">Create New Member</h3>
			    	@endif
		    	@endif
		    </div>
		    <div class="box-body">
		        @include('admin.custom-page.parts.forms._create_edit_team_member_form')
		    </div>
		    <!-- /.box-body -->
		</div>
	</div>
	<div class="clearfix"></div>

	
@endsection
@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

	<script src="{{ asset('admin/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

	{!! JsValidator::formRequest('App\Http\Requests\Admin\TeamRequest') !!}
	<script>
        $('select').select2();
		
	  	$(function () {
	    	CKEDITOR.replace('editor');
	    	$('select').select2();
	  	})
	</script>
@endsection()
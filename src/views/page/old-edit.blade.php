@extends('admin.layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-header with-border">
	        <h3 class="box-title">Edit Page</h3>
	        <a href="{{ action("Admin\PagesController@create") }}" class="btn btn-primary btn-flat pull-right ">Add New</a>
	        @if(isset($parent_lang_id) || isset($page) && $page->lang == 'arm')
	        	@if(isset($parent_lang_id))
	        		<a href="{{ action("Admin\PagesController@edit, [$parent_lang_id]") }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
	        	@else
	        		<a href="{{ action("Admin\PagesController@edit", [$page->parent_lang_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
	        	@endif
	        @else
	        	<a href="{{ action("Admin\PagesController@translate", [$page->id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to Armenian</a>
	        @endif
	    </div>
	    <div class="box-body">
	        @include('admin.page.parts.forms._create_edit_form')
	    </div>
	    <!-- /.box-body -->
	</div>
@endsection
@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('admin/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

	<script src="{{ asset('admin/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

	{{-- {!! JsValidator::formRequest('App\Http\Requests\Admin\PageRequest') !!} --}}
	<script>
	  	$(function () {
	  		if($('textarea').is('editor')){
	    		CKEDITOR.replace('editor');	  			
	  		}
	    	if($('textarea').is('editor-2')){
	    		CKEDITOR.replace('editor-2');
	    	}
	    	$('select').select2();
	  	})

	  	$('#template').on('change', function(e){
	  		e.preventDefault();
	  		var conf = confirm('By chnaging the page template you data will be lost. Are you sure you want to chnage it?');
	  		if(conf == true){
	  			window.location.href = window.location.origin+'/'+window.location.pathname+'?template='+this.value
	  		}
	  	});
	</script>
@endsection()
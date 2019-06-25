{{-- {!! dd(json_decode($page->description)) !!} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-header with-border">
	    	@if(!isset($page))
	        	<h3 class="box-title">Create New Page</h3>
	        @else
				<input type="hidden"  id="page_id" value="{{$page->id}}">
				<h3 class="box-title">Edit Page</h3>
				<a href="{{ route('page-create') }}" class="btn btn-primary btn-flat pull-right ">Add New</a>
{{--				@if(isset($parent_lang_id) || isset($page) && $page->lang == 'arm')--}}
{{--					@if(isset($parent_lang_id))--}}
{{--						<a href="{{ route('page-edit', [$parent_lang_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>--}}
{{--					@else--}}
{{--						<a href="{{ route('page-edit', $page->parent_lang_id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>--}}
{{--					@endif--}}
{{--				@else--}}
{{--					<a href="{{ route('page-translate',$page->id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to Armenian</a>--}}
{{--				@endif--}}

{{--				<a href="{{ route('page-translate', [$page->id, $page->language_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate</a>--}}
			@endif
	    </div>
	    <div class="box-body">
	        @include('admin-panel::page.parts.forms._create_edit_form')
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
	
	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\PageRequest') !!}
	<script>
        $('select').select2();
	  	$(function () {
	  		
	    	if($('#content').length > 0){
	    		CKEDITOR.replace('content');	  			
	  		}
	  		if($('#principles').length > 0){
	    		CKEDITOR.replace('principles');	  			
	  		}
	  		if($('#courses').length > 0){
	    		CKEDITOR.replace('courses');	  			
	  		}
	  		if($('#banner').length > 0){
	    		CKEDITOR.replace('banner');	  			
	  		}
	  		if($('#company').length > 0){
	    		CKEDITOR.replace('company');	  			
	  		}
	  		if($('#image').length > 0){
	    		CKEDITOR.replace('image');	  			
	  		}
	  		if($('#table').length > 0){
	    		CKEDITOR.replace('table');	  			
	  		}
	  	})
  	
	  	$('body').off('click', '.clone').on('click', '.clone', function(e){
	  		e.preventDefault();
	  		var order = $(this).parent().children(':nth-last-child(2)').index() + 1;
	  		var clone = $(this).parent().children(':nth-last-child(2)').clone().insertBefore($(this));
	  		clone.find('.question').attr('name', 'meta[faq]['+ order +'][1]');
	  		clone.find('.answer').attr('name', 'meta[faq]['+ order +'][2]');
	  		
	  	});

  	 	$('body').off('click', '.remove-faq').on('click', '.remove-faq', function(e){
	  		e.preventDefault();
	  		$(this).parent().remove();
	  		$('.question').each(function(){
	  			new_order = $(this).parent().parent().index();
	  			$(this).attr('name', 'meta[faq]['+ new_order +'][1]')
	  		});
	  		$('.answer').each(function(){
	  			new_order = $(this).parent().parent().index();
	  			$(this).attr('name', 'meta[faq]['+ new_order +'][2]')
	  		});
	  	});
	  	


	  	$('body').off('change', '#template').on('change', '#template', function(e){
	  		e.preventDefault();
	  		var conf = confirm('By chnaging the page template your data will be lost. Are you sure you want to chnage it?');
	  		if(conf == true){
	  			window.location.href = app.ajax_url+window.location.pathname+'?template='+this.value
	  		}
	  	});

	  	// if(typeof builderOptions != "undefined"){
 			// $('body').off('submit', 'form').on('submit', 'form', function(e){
		  // 		e.preventDefault();
		  // 		var form = $(this);
		  // 		var form_data = form.serializeArray();
		  // 		form_data.push({name: 'description', 'value': JSON.stringify(builderOptions)}); 
		  // 		console.log(form_data);
		  // 		$.ajax({
		  // 		    type: 'POST',
		  // 		    url: form.attr('action'),
		  // 		    dataType: 'JSON',
		  // 		    // data: {'content' '_token' : $('meta[name="csrf-token"]').attr('content')},
		  // 		    data: form_data,
		  // 		    success: function(data){
		  // 		    	console.log(data); 
	   //                  // window.location = "/admin/pages/edit/" + data.page_id
		  // 		    }
		  // 		});
		  // 	});
	  	// }

		
	  </script>
	  <!-- <script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script> -->
@endsection()
{{-- {!! dd(json_decode($page->description)) !!} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<style>
		.draggables, .dragged {
			border: 1px solid #eee;
			width: 100%;
			min-height: 20px;
			list-style-type: none;
			margin: 0;
			padding: 5px 0 0 0;
			float: left;
			margin-right: 10px;
		}
		.draggables li, .dragged li {
			margin: 0 5px 5px 5px;
			/*padding: 5px;*/
			font-size: 1.2em;
			width: 100%;
		}
	</style>
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
			@endif
	    </div>
	    <div class="box-body">
	        @include('admin-panel::page.parts.forms._create_edit_form')
	    </div>
	    <!-- /.box-body -->
	</div>
@endsection
@section('script')
    <script type="text/javascript">
		//Content Builder
	    @if(isset($page) && $page->is_content_builder)
			builderOptions = {!! $page->content_builder !!};
			console.log('builderOptions', builderOptions);
			if(Object.keys(builderOptions).length === 0 && builderOptions.constructor === Object){
	    		builderOptions = [];
			}
	    @else
	    	builderOptions = [];
	    @endif
    </script>
	<!-- Select2 -->
	<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

	<script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	<script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>
	
	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\PageRequest') !!}
	<script>
		$(document).ready(function(){
	        $('select').select2();
		  	$(function () {
		    	if($('#content').length > 0){
		    		CKEDITOR.replace('content');
		  		}
		  	})

			$( function() {
				$( ".draggables, .dragged" ).sortable({
					connectWith: ".connectedSortable"
				}).disableSelection();
			} );

		  	$('body').off('change', '#template').on('change', '#template', function(e){
		  		e.preventDefault();
		  		var conf = confirm('By chnaging the page template your unsaved data will be lost. Are you sure you want to change it?');
		  		if(conf == true){
		  			window.location.href = app.ajax_url+window.location.pathname+'?template='+this.value
		  		}
		  	});

			$('.gallery-show-container').each(function(){
				var gallery_id = $(this).data('meta');

				if (window.hasOwnProperty('galleryObj')){
					currentGallery = galleryObj[gallery_id];
				}

				var sortable = Sortable.create(this, {
					// Element dragging ended
					onEnd: function (evt) {
						var itemEl = evt.item;  // dragged HTMLElement
						evt.to;    // target list
						evt.from;  // previous list
						evt.oldIndex;  // element’s old index within old parent
						evt.newIndex;  // element’s new index within new parent
						old_index = evt.oldIndex - 1;
						new_index = evt.newIndex - 1;

						function arrayMove(array, old_index, new_index) {
							if (new_index >= array.length) {
								var k = new_index - array.length;
								while ((k--) + 1) {
									array.push(undefined);
								}
							}
							array.splice(new_index, 0, array.splice(old_index, 1)[0]);
							return array; // for testing purposes
						};
						// console.log(old_index, new_index);
						if(currentGallery){
							console.log(currentGallery,1)
							currentGallery = arrayMove(currentGallery, old_index, new_index);
						} else {
							console.log(galleryImagesArr,2)
							currentGallery = arrayMove(galleryImagesArr, old_index, new_index);
						}

						if (gallery_id) {
							$('.gallery-container[data-id='+gallery_id+']').find('.meta_images').val(JSON.stringify(currentGallery));
						} else {
							$('#images').val(JSON.stringify(currentGallery));
						}
					},
				});
			});

			// $('body').off('submit','form').on('submit', 'form', function (e) {
			// 		e.preventDefault();
			// 		var attachments = {};
			// 		$('.dragged').each(function(){
			// 			if($(this).closest('.panel-default.attachments').find('.check-all').is(":checked")){
			// 				attachments[$(this).closest('.panel-default.attachments').data('model')] = 'all';
			// 			} else {
			// 				var ids = $(this).find('li').map(function() {
			// 					return $(this).data("id");
			// 				}).get().join();
			// 				attachments[$(this).closest('.panel-default.attachments').data('model')] = ids;
			// 			}
			// 		})
			// 		$('#attachments').val(JSON.stringify(attachments));
			// 		$(this)[0].submit();
			// });


		  	if(typeof builderOptions != "undefined"){
	 			$('body').off('submit', 'form').on('submit', 'form', function(e){
			  		e.preventDefault();
			  		var form = $(this);
			  		var form_data = form.serializeArray();
			  		form_data.push({ name: 'content_builder', 'value': JSON.stringify(builderOptions) }); 
			  		
			  		$.ajax({
			  		    type: 'POST',
			  		    url: form.attr('action'),
			  		    dataType: 'JSON',
			  		    // data: {'content' '_token' : $('meta[name="csrf-token"]').attr('content')},
			  		    data: form_data,
			  		    success: function(data){
			  		    	initToastr(data.message, data.status);
		                    if(data.redirect_url){
								setTimeout(function(){
									window.location = data.redirect_url;
								},500);
							}
			  		    }
			  		});
			  	});
		  	}
		});
	</script>
	<script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script>
@endsection()
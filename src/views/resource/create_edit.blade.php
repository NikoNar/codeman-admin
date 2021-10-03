{{-- {!! dd(json_decode($resource->description)) !!} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
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
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-header with-border">
	    	@if(!isset($resource))
	        	<h3 class="box-title">Create {{Str::singular(ucwords($module))}}</h3>
	        @else
				<input type="hidden"  id="resource_id" value="{{$resource->id}}">
				<h3 class="box-title">Edit {{Str::singular(ucwords($module))}}</h3>
				<a href='{{ url("admin/resource/$module/create") }}' class="btn btn-primary btn-flat pull-right ">Add New</a>
			@endif
	    </div>
	    <div class="box-body">
	        @include('admin-panel::resource.parts.forms._create_edit_form')
	    </div>
	    <!-- /.box-body -->
	</div>

@endsection
@section('script')
	<script type="text/javascript">
		//Content Builder
	    @if(isset($resource))
			builderOptions = {!! $resource->content_builder !!};
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
	
	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\ResourceRequest') !!}
	<script>
        $('select').select2();
	  	$(function () {
	  		if($('.editor').length > 0){
				CKEDITOR.replaceClass('.editor');
			}
	  	})
	</script>
	<script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>

	<script>
		$( function() {
			$( ".draggables, .dragged" ).sortable({
				connectWith: ".connectedSortable"
			}).disableSelection();
		} );

		$('body').off('submit', 'form').on('submit', 'form', function (e) {
			if($(this).attr('id') != 'store_category') {
				e.preventDefault();
				var relations = {};
				var ids = $('.dragged').find('li').map(function () {
					relations[$(this).data('id')] = {'resourceable_type': $(this).closest('ul').data('name')};
				});
				$('#relations').val(JSON.stringify(relations));
			}

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

		$('.gallery-show-container').each(function(){
			var gallery_id = $(this).data('meta');
			if (window.hasOwnProperty('galleryObj')){
				var currentGallery = galleryObj[gallery_id];
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
						currentGallery = arrayMove(currentGallery, old_index, new_index);
					} else {
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
	</script>
	<script>
		function myFunction() {
			// Declare variables
			var input, filter, ul, li, a, i, txtValue;
			input = document.getElementById('myInput');
			filter = input.value.toUpperCase();
			ul = document.getElementById("myUL");
			li = ul.getElementsByTagName('li');

			// Loop through all list items, and hide those who don't match the search query
			for (i = 0; i < li.length; i++) {
				a = li[i].getElementsByTagName("a")[0];
				txtValue = a.textContent || a.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					li[i].style.display = "";
				} else {
					li[i].style.display = "none";
				}
			}
		}
	</script>
	<script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script>
@endsection()
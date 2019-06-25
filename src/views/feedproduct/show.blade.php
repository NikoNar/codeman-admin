@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
<div class="box">
    <div class="box-body">
    	<div class="clearfix"></div>
			<div id="resource-container">
				@if(isset($product))
					<div>
						<h2>{{$product->name}}</h2>
						@foreach($product->variations as $var)  
					 	<!-- <span>Variation: {{$var->variation_id}}</span><br> -->
					 	
					 	<!-- <span>Size:{{$var->size}}</span> -->
					 	<hr>
	

						<div class="form-group" style="max-width:300px;">
						    <div class="box-header with-border">
						    	<span>Color: {{$var->color}}</span>
						        <span class="btn btn-file btn-primary btn-flat media-open multichoose pull-right" >
					    	        <span class="fileupload-new">Select Images</span>
					    	        {!! Form::hidden('gallery', null, ['id' => 'images']); !!}
					    	    </span>
						    </div>
					    </div>
						@php								
							$gallery = json_decode($var->images)
						@endphp
					
				    	<div class="clearfix"></div>
				    	   <div class="gallery-show-container col-md-3-gallery" id="sortable-grid">
			    	     
							@if(!empty($gallery))
			    	            @foreach($gallery as $image)
			    	                <div class="media-item">
			    	                    <i class="fa fa-times-circle remove"></i>
			    	                    <i class="fa fa-arrows-alt gallery-image-sort"></i>
			    	                    	<img src="{!! $image !!}" class="thumbnail">    	                   
			    	                  <!--  <input name="thumbnail-alt" class="form-control" placeholder="Alt Name"> -->
			    	                </div>
			    	            @endforeach
				            @endif
				    	</div>			
					</div>
					@endforeach	
				@endif						
			</div>			
		<div class="clearfix"></div>
	</div>
</div>
@endsection

@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

	{{-- <script src="{{ asset('admin/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script> --}}

	<script src="{{ asset('admin/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	
	<!-- date-range-picker -->
	{{-- <script src="{{ asset('admin/bower_components/moment/min/moment.min.js') }} "></script> --}}
	
	{{-- <script src="{{ asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }} "></script> --}}
	<!-- bootstrap datepicker -->
	{{-- <script src="{{ asset('admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }} "></script> --}}
	<!-- bootstrap color picker -->
	{{-- <script src="{{ asset('admin/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script> --}}
	<!-- bootstrap time picker -->
	{{-- <script src="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }} "></script> --}}
	
	<!-- iCheck 1.0.1 -->
	{{-- <script src="{{ asset('admin/plugins/iCheck/icheck.min.js') }}"></script> --}}

	{{-- {!! JsValidator::formRequest('Codeman\Admin\Http\Requests\ProductRequest') !!} --}}
	<script>
	  	$(function () {
	    	CKEDITOR.replace('editor');
	    	// CKEDITOR.replace('short_description_editor');
	    	// CKEDITOR.replace('tips_for_using_editor');
	    	// CKEDITOR.replace('how_the_tool_works_editor');
	    	// CKEDITOR.replace('awards_and_prizes_editor');
	    	$('select').select2();
	  	})
	  	// $('#datepicker').datepicker({})
	  	//Timepicker
	  	// $('#timepicker').timepicker({
      		// showInputs: false
	  	// })
	</script>
	<!-- Sortable -->
	<script src="{{ asset('admin/plugins/sortable/Sortable.min.js') }} "></script>	
	
	<script>
		if(typeof galleryImagesArr !== undefined && typeof galleryImagesArr !== "undefined"){
			if(Array.isArray(galleryImagesArr)){
	  			$('#images').val(JSON.stringify(galleryImagesArr));
			}
		}else{
			galleryImagesArr = [];
	  		// $('#images').val(galleryImagesArr);
		}

	  	sortEl = document.getElementById('sortable-grid');
	  	var sortable = Sortable.create(sortEl, {
	  		// Element dragging ended
  			onEnd: function (evt) {
  				var itemEl = evt.item;  // dragged HTMLElement
  				evt.to;    // target list
  				evt.from;  // previous list
  				evt.oldIndex;  // element's old index within old parent
  				evt.newIndex;  // element's new index within new parent
  				old_index = evt.oldIndex - 1;
  				new_index = evt.newIndex - 1;

  				function arrayMove (array, old_index, new_index) {
  				    if (new_index >= array.length) {
  				        var k = new_index - array.length;
  				        while ((k--) + 1) {
  				            array.push(undefined);
  				        }
  				    }
  				    array.splice(new_index, 0, array.splice(old_index, 1)[0]);
  				    return array; // for testing purposes
  				};
  				console.log(old_index, new_index);
  				galleryImagesArr = arrayMove(galleryImagesArr, old_index, new_index);
  				$('#images').val(JSON.stringify(galleryImagesArr));

  			},
	  	});
	  </script>
@endsection()
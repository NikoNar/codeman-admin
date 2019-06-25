@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
<div class="box">
    <div class="box-body">
		@if(isset($product))
			{!! Form::model($product, ['route' => ['feed-product-update', $product->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
			{!! Form::hidden('id', $product->id) !!}			
		@endif
		<div class="col-md-6 border-right">
			<div class="form-group">
				{!! Form::label('name', 'Name') !!}
				<div class='input-group'>
				    <span class="input-group-addon">
				        <span class="fa fa-font"></span>
				    </span>
					{!! Form::text('name', null, ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6 no-padding-left">
				<div class="form-group">
					{!! Form::label('upc', 'UPC') !!}
						{!! Form::text('upc', null, ['class' => 'form-control', 'readonly']) !!}
				</div>
			</div>
			<div class="col-md-6 no-padding-right">
				<div class="form-group">
					{!! Form::label('brand_name', 'Brand Name') !!}
						{!! Form::text('brand_name', null, ['class' => 'form-control', 'readonly']) !!}
				</div>
			</div>
			

			<div class="clearfix"></div>
			<br>
			<div class="form-group">

				{!! Form::label('description', 'Description'); !!}
				{!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}
			</div>
			<hr>
		
			<div class="clearfix"></div>
			
			<div class="form-group" style="max-width:300px;">
				{!! Form::label('thumbnail', 'Featured Image'); !!}
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-preview thumbnail" style="width: 100%;">
						@if(isset($product) )
				  			<img src="{{$product->image}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
						@else
				  			<img src="{{asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
						@endif
				  	</div>
				  	<div>
				    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
				    		<span class="fileupload-new">Select image</span>
							{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
							{!! Form::hidden('image', null, ['id' => 'thumbnail']); !!}
						</span>
				    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
				  		<div class="clearfix"></div>
				  	</div>
				</div>
			</div>
			<!-- <div class="box">
			    <div class="box-header with-border">
			        <h3 class="box-title">SEO Options</h3>
			    </div>
			    <div class="box-body">
			    	<div class="form-group">
			    		{!! Form::label('meta-title', 'Meta Title'); !!}
			    		{!! Form::text('meta-title', null, ['class' => 'form-control', 'placeholder' => '%title% | %sitename%']) !!}
			    	</div>
			    	<div class="form-group">
			    		{!! Form::label('meta-description', 'Meta Description'); !!}
			    		{!! Form::text('meta-description', null, ['class' => 'form-control']) !!}
			    	</div>
			    	<div class="form-group">
			    		{!! Form::label('meta-keywords', 'Meta Keywords'); !!}
			    		{!! Form::text('meta-keywords', null, ['class' => 'form-control', 'data-role' => "tagsinput" ]) !!}
			    	</div>
			    </div>
			</div> -->
		</div>
		<div class="col-md-6">
			
			
			 @foreach($product->variations as $var)  

			<hr>
			<div class="form-group">
			    <div class="box-header with-border">
					{!! Form::label('gallery', 'Gallery'); !!}
			        <span class="btn btn-file btn-primary btn-flat media-open multichoose pull-right" >
		    	        <span class="fileupload-new">Select Images</span>
		    	        {!! Form::hidden('gallery', null, ['id' => 'images']); !!}
		    	        {!! Form::hidden('prod_variation_id', $var->id); !!}
		    	    </span>
			    </div>
			    <div>
					@php
						$gallery = json_decode($var->images);

					@endphp
					
			    	<div class="clearfix"></div>
			    	   <div class="gallery-show-container col-md-3-gallery" id="sortable-grid">
		    	        <div class="empty-gallery" style="padding-top: 20px; color: #fff; text-transform: uppercase; font-size: 14px; text-align: center; min-height: 300px; background: linear-gradient(-140deg, #36fcef 0%, #1fc8db 51%, #2cb5e8 75%); {{isset($gallery) && !empty($gallery) ? 'display: none;' : null }}">
		    	         <i class="fa fa-arrow-up" style="font-size: 14px; margin-right: 10px; margin-left: 10px;"></i>
		    	         Select Images for fill the gallery.
		    	        </div>
						@if(!empty($gallery))
		    	            @foreach($gallery as $image)

		    	                <div class="media-item">
		    	                    <i class="fa fa-times-circle remove"></i>
		    	                    <i class="fa fa-arrows-alt gallery-image-sort"></i>
		    	                    
		    	                    	<img src="{{$image->url}}" class="thumbnail">
		    	                
		    	                    <input name="thumbnail-alt" class="form-control"  placeholder="Alt Name" value="{!! isset($image->alt) ? $image->alt : '' !!}">
		    	                </div>
		    	            @endforeach
			            @endif

			    	</div>

			    	@if(isset($gallery) && !empty($gallery))
			    	    <script type="text/javascript">
			    	        var galleryImagesArr = [];
			    	        @foreach($gallery as $image)
			    	        	galleryImagesArr.push({'url': '{!! $image->url !!}' , 'alt' : '{!! isset($image->alt) ? $image->alt : '' !!}' });
			    	        @endforeach
			    	        console.log(JSON.stringify(galleryImagesArr));
			    	    </script>
			    	@endif
			    </div>
			</div>
			@endforeach
			<!-- <hr> -->
			<!-- <div class="">
				@if(isset($order) && !empty($order))
					<div class="form-group">
						{!! Form::label('order', 'Order'); !!}
						{!! Form::number('order', $order, ['class' => 'form-control']) !!}
					</div>
				@else
					<div class="form-group">
						{!! Form::label('order', 'Order'); !!}
						{!! Form::number('order', null, ['class' => 'form-control']) !!}
					</div>
				@endif
			</div> -->
			<div class="clearfix"></div>
			<hr>
			<div class="form-group">
				@if(isset($product))
					{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
				@else
					{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
				@endif
			</div>
		</div>


		{!! Form::close() !!}
	</div>
</div>
@endsection
@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

	{{-- <script src="{{ asset('admin/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script> --}}

	<script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
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
	<script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>	
	
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
@if(isset($product))
{{-- {{dd($page)}} --}}
	{!! Form::model($product, ['route' => ['product-update', $product->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $product->id) !!}
@else
	{!! Form::open(['route' => 'product-store', 'enctype' => "multipart/form-data"]) !!}
	@if(isset($parent_lang_id))
		{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
	@endif
@endif
<div class="col-md-9 border-right">
	<div class="form-group">
		{!! Form::label('title', 'Title') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12 no-padding">
			{!! Form::label('slug', 'Slug(Permalink)'); !!}
			<div class='input-group'>
			    <span class="input-group-addon">
			    	@if(isset($slug))
			        	<a href="{!! url('/products/'.$slug) !!}"><span class="fa fa-eye"></span></a>
			    	@else
			    		<span class="fa fa-link"></span>
			    	@endif
			    </span>
			     <span class="input-group-addon no-border-right">
			        <i>{{ URL::to('/products') }}/</i>
			    </span>
				{!! Form::text('slug', null, ['class' => 'form-control']) !!}
				<span class="input-group-addon">
					@if(isset($product))
				    	<a href="{!! url('/products/'.$product->slug) !!}" target="_blank"><span class="fa fa-eye"></span></a>
					@else
						<span class="fa fa-eye"></span>
					@endif
				</span>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>
	<br>
	<div class="form-group">

		{!! Form::label('description', 'Content'); !!}
		{!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}
	</div>
	<hr>
	<div class="clearfix"></div>
	<div class="box">
	    <div class="box-header with-border">
	        <ul class="nav nav-tabs">
	          	<li class="active"><a data-toggle="tab" href="#general_info">General Info</a></li>
	          	<li><a data-toggle="tab" href="#description">Description</a></li>
	          	<li><a data-toggle="tab" href="#tips_for_using">Tips for using</a></li>
	          	<li><a data-toggle="tab" href="#how_the_tool_works">How the Product works</a></li>
	          	<li><a data-toggle="tab" href="#awards_and_prizes">Awards and Prizes</a></li>
	          	<li><a data-toggle="tab" href="#colors">Colors</a></li>
	        </ul>
	    </div>
	    <div class="box-body">


	    	<div class="tab-content">
	    	  	<div id="general_info" class="tab-pane fade in active">
	    	    	<div class="form-group col-md-3">
	    	    		{!! Form::label('weight', 'Weight') !!}
	    	    		<div class='input-group'>
	    	    		    <span class="input-group-addon">
	    	    		        <span class="fa fa-balance-scale"></span>
	    	    		    </span>
	    	    			{!! Form::text('weight', null, ['class' => 'form-control']) !!}
	    	    		</div>
	    	    	</div>
	    	    	<div class="form-group col-md-3">
	    	    		{!! Form::label('regular_price', 'Regular Price') !!}
	    	    		<div class='input-group'>
	    	    			{!! Form::text('regular_price', null, ['class' => 'form-control']) !!}
	    	    			<span class="input-group-addon">
	    	    			    AMD
	    	    			</span>
	    	    		</div>
	    	    	</div>
	    	    	<div class="form-group col-md-3">
	    	    		{!! Form::label('sale_price', 'Sale Price') !!}
	    	    		<div class='input-group'>
	    	    			{!! Form::text('sale_price', null, ['class' => 'form-control']) !!}
	    	    			<span class="input-group-addon">
	    	    			    AMD
	    	    			</span>
	    	    		</div>
	    	    	</div>
	    	    	<div class="form-group col-md-3">
	    	    		{!! Form::label('in_stock', 'In Stock') !!}
	    	    		<div class='input-group'>
	    	    			{!! Form::select('in_stock', ['1' => 'In Stock', '0' => 'Out Of Stock'], null, ['class' => 'form-control']) !!}
	    	    		</div>
	    	    	</div>
	    	  	</div>
	    	  	<div id="description" class="tab-pane fade">
	    	    	<h4>Short Descriotion</h4>
					{!! Form::textarea('short_description', null, ['class' => 'form-control', 'id' => 'short_description_editor']); !!}
	    	  	</div>
	    	  	<div id="tips_for_using" class="tab-pane fade">
	    	    	<h4>Tips for using</h4>
	    	    	{!! Form::textarea('tips_for_using', null, ['class' => 'form-control', 'id' => 'tips_for_using_editor']); !!}
	    	  	</div>
	    	  	<div id="how_the_tool_works" class="tab-pane fade">
	    	    	<h4>How the Product works</h4>
	    	    	{!! Form::textarea('how_the_tool_works', null, ['class' => 'form-control', 'id' => 'how_the_tool_works_editor']); !!}
	    	  	</div>
	    	  	<div id="awards_and_prizes" class="tab-pane fade">
	    	    	<h4>Awards and Prizes</h4>
	    	    	{!! Form::textarea('awards_and_prizes', null, ['class' => 'form-control', 'id' => 'awards_and_prizes_editor']); !!}
	    	  	</div>
				<div id="colors" class="tab-pane fade">
					@include('admin-panel::custom-page.parts.colors')
	    	  	</div>
	    	</div>
	    </div>
	</div>
	<hr>
	<div class="clearfix"></div>
	<div class="box">
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
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		{!! Form::label('created_at', 'Published Date'); !!}
		<div class="clearfix"></div>
        <div class='input-group col-md-6 pull-left'>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        	{!! Form::text('published_date', null, ['class' => 'form-control', 'id' => 'datepicker']) !!}
        </div>
        <div class="input-group bootstrap-timepicker col-md-6 pull-left">
        	{!! Form::text('published_time', null, ['class' => 'form-control timepicker', 'id' => 'timepicker']) !!}
        	<div class="input-group-addon">
        		<i class="fa fa-clock"></i>
        	</div>
        </div>
		<div class="clearfix"></div>
	</div>
	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'unpublished' => 'Unpublished', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>
	{{-- <div class="form-group">
		{!! Form::label('lang', 'Language'); !!}
		@if(isset($parent_lang_id) || (isset($product) && $product->lang == 'arm'))
			{!! Form::select('lang', ['arm' => 'Հայերեն'], null, ['class' => 'form-control select2', 'readonly']); !!}
		@else
			{!! Form::select('lang', ['en' => 'English'], null, ['class' => 'form-control select2', 'readonly']); !!}

		@endif
	</div> --}}
	<div class="col-md-12 no-padding">
		{!! Form::label('categories', 'Categories') !!}
		<div class='input-group'>
			<span class="input-group-addon">
			    <span class="glyphicon glyphicon-tag"></span>
			</span>

			@if(isset($product) &&  null != $product_categories = $product->singleProductcategories()->get()->pluck('category_id')->toArray())
				@if(!empty($product_categories))
	    			@include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple', 'selected' => $product_categories])
				@else
	    			@include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
				@endif
			@else
	    		@include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
			@endif
		</div>
		<div class="clearfix"></div>
		<a href="#" class="pull-right" id="add-category" data-type="product"><i class="fa fa-plus"></i> Add New Category</a>
	</div>
	<div class="clearfix"></div>

	<div class="form-group">
		{!! Form::label('thumbnail', 'Featured Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($product) && !empty($product->thumbnail))
		  			<img src="{{$product->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
				@else
		  			<img src="{{asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
				@endif
		  	</div>
		  	<div>
		    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
		    		<span class="fileupload-new">Select image</span>
					{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
					{!! Form::hidden('thumbnail', null, ['id' => 'thumbnail']); !!}
				</span>
		    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
		  		<div class="clearfix"></div>
		  	</div>
		</div>
	</div>
	<hr>
	<div class="form-group">
	    <div class="box-header with-border">
			{!! Form::label('gallery', 'Gallery'); !!}
	        <span class="btn btn-file btn-primary btn-flat media-open multichoose pull-right" >
    	        <span class="fileupload-new">Select Images</span>
    	        {!! Form::hidden('gallery', null, ['id' => 'images']); !!}
    	    </span>
	    </div>
	    <div>
	        @if(isset($product->gallery) && !empty($product->gallery) && isJson($product->gallery))
				@php
					$gallery = json_decode($product->gallery);
				@endphp
			@endif
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
    	                    @if(is_url($image->url))
    	                    	<img src="{!! $image->url !!}" class="thumbnail">
    	                    @else
    	                    	<img src="{!! url('media/icon_size').'/'.$image->url !!}" class="thumbnail">
    	                    @endif
    	                    <input name="thumbnail-alt" class="form-control" value="{!! $image->alt !!}" placeholder="Alt Name">
    	                </div>
    	            @endforeach
	            @endif

	    	</div>

	    	@if(isset($gallery) && !empty($gallery))
	    	    <script type="text/javascript">
	    	        var galleryImagesArr = [];
	    	        @foreach($gallery as $image)
	    	        	galleryImagesArr.push({'url': '{!! $image->url !!}', 'alt': '{!! $image->alt !!}' });
	    	        @endforeach
	    	        console.log(JSON.stringify(galleryImagesArr));
	    	    </script>
	    	@endif
	    </div>
	</div>
	<hr>
	<div class="">
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
	</div>
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
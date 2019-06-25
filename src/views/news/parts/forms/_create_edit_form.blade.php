@if(isset($news))
{{-- {{dd($page)}} --}}
	{!! Form::model($news, ['route' => ['news-update', $news->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $news->id) !!}
@else
	{!! Form::open(['route' => 'news-store', 'enctype' => "multipart/form-data", 'id' => 'news-store']) !!}
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
		<div class="col-md-6 no-padding">
			{!! Form::label('slug', 'Slug(Permalink)'); !!}
			
			<div class='input-group'>
			    <span class="input-group-addon">
			    	@if(isset($news) && !empty($news))
				    	<a href="{!! url('/almanac', $news->categories()->first()->title.'/'.$news->slug ) !!}" target="_blank">
							<span class="fa fa-chain"></span>
						</a>
		    		@else
		    			<span class="fa fa-chain"></span>
		    		@endif
			        
			    </span>

			     <span class="input-group-addon no-border-right">
			     	@if(isset($news) && !empty($news))
			        	<i>{{ URL::to('/almanac/'.$news->categories()->first()->title) }}/</i>
		        	@endif
			    </span>
				{!! Form::text('slug', null, ['class' => 'form-control']) !!}
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
	<br>
	@include('admin-panel::layouts.content-builder.index', ['type' => 'blog'])
	<!-- <div class="form-group">

		{!! Form::label('description', 'Content'); !!}
		{!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}
	</div> -->
	<div class="form-group">

		{!! Form::label('excerpt', 'Excerpt'); !!}
		{!! Form::text('excerpt', null, ['class' => 'form-control']); !!}
	</div>
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
        		<i class="fa fa-clock-o"></i>
        	</div>
        </div>
		<div class="clearfix"></div>
	</div>
	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>
	<!-- <div class="form-group">
		{!! Form::label('lang', 'Language'); !!}
		@if(isset($parent_lang_id) || (isset($news) && $news->lang == 'arm'))
			{!! Form::select('lang', ['arm' => 'Հայերեն'], null, ['class' => 'form-control select2', 'readonly']); !!}
		@else
			{!! Form::select('lang', ['en' => 'English'], null, ['class' => 'form-control select2', 'readonly']); !!}
			
		@endif
	</div> -->
	<div class="col-md-12 no-padding">
		{!! Form::label('categories_id', 'Categories') !!}
		<div class="form-group">
			<div class='input-group'>
				<span class="input-group-addon">
				    <span class="glyphicon glyphicon-tag"></span>
				</span>

				@if(isset($news) &&  null != $news_categories = $news->categories()->get()->pluck('id')->toArray())
					@if(!empty($news_categories))
		    			@include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple', 'selected' => $news_categories])
					@else
		    			@include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
					@endif
				@else
		    		@include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
				@endif
			</div>

		<div class="clearfix"></div>
		<a href="#" class="pull-right" id="add-category" data-type="news"><i class="fa fa-plus"></i> Add New Category</a>
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('thumbnail', 'Featured Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($news) && !empty($news->thumbnail))
		  			<img src="{{$news->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
				@else
		  			<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
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
		@if(isset($news))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>

		
{!! Form::close() !!}

@if(isset($program)  && !isset($parent_lang_id))
	{!! Form::model($program, ['route' => ['program-update', $program->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $program->id) !!}
@elseif(isset($program) && isset($parent_lang_id) )
	{!! Form::model($program, ['route' => 'program-store', 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'program-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
	{!! Form::open(['route' => 'program-store', 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif

<div class="col-md-9 border-right">
	<div class="form-group">
		{!! Form::label('title', 'Name') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	
	<div class="clearfix"></div>
	<br>
	
	<div class="form-group">
		{!! Form::label('start_date', 'Starts on'); !!}
		{!! Form::text('start_date', null, ['class' => 'form-control', 'name'=>'start_date']); !!}
	</div>

	<div class="form-group">		
		{!! Form::label('duration', 'Duration'); !!}
		{!! Form::text('duration', null, ['class' => 'form-control', 'name'=>'duration']); !!}
	</div>
		<div class="form-group">		
		{!! Form::label('frequency', 'Frequency'); !!}
		{!! Form::text('frequency', null, ['class' => 'form-control', 'name'=>'frequency']); !!}
	</div>
	<div class="form-group">
		{!! Form::label('price', 'Price'); !!}
		{!! Form::text('price', null, ['class' => 'form-control',  'name' =>  'price']); !!}	
	</div>
	<div class="form-group">
		{!! Form::label('description', 'Description'); !!}
		{!! Form::textarea('description', null, ['class' => 'form-control',  'name' =>  'description', 'id' => 'editor']); !!}	
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
{{--	 <div class="form-group">--}}
{{--		{!! Form::label('lang', 'Language'); !!}--}}
{{--		@if(isset($parent_lang_id) || (isset($program) && $program->lang == 'arm'))--}}
{{--			{!! Form::select('lang', ['arm' => 'Հայերեն'], null, ['class' => 'form-control select2', 'readonly']); !!}--}}
{{--		@else--}}
{{--			{!! Form::select('lang', ['en' => 'English'], null, ['class' => 'form-control select2', 'readonly']); !!}--}}
{{--			--}}
{{--		@endif--}}
{{--	</div> --}}
	<div class="form-group">
		@if(isset($languages) && !empty($languages))
			<input type="hidden" name="resource_id" value="{{$program->id}}">
			{!! Form::label('language_id', 'Language'); !!}
			{!! Form::select('language_id', $languages, isset($language_id) ? $language_id : null, ['class' => 'form-control select2 languages']); !!}
		@endif
	</div>


	<div class="form-group">
		{!! Form::label('thumbnail', 'Featured Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($program) && !empty($program->thumbnail))
		  			<img src="{{$program->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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
		@if(isset($program))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>

		
{!! Form::close() !!}

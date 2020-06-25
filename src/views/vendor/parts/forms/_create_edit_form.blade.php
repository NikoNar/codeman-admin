
@if(isset($vendor)  && !isset($parent_lang_id))
    {!! Form::model($vendor, ['url' => ["admin/vendor",$vendor->id ], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
    {!! Form::hidden('id', $vendor->id) !!}
@elseif(isset($vendor) && isset($parent_lang_id) )
    {!! Form::model($vendor, ['url' => "admin/vendor", 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'vendor-store']) !!}
    {!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
    {!! Form::open(['url' => "admin/vendor", 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
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

	<div class="clearfix"></div>
	<br>
	<div class="form-group">

{{--		{!! Form::label('description', 'Content'); !!}--}}
{{--		{!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}--}}

        <br>
        {!! Form::label('phone', 'Phone'); !!}
        {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'code']); !!}
    </div>
	<hr>
	<div class="clearfix"></div>
	<hr>
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
    <div class="form-group">
        @if(isset($languages) && !empty($languages))
            @isset($vendor)
                <input type="hidden" name="vendor_id" value="{{$vendor->id}}">
            @endif
            {!! Form::label('lang', 'Language'); !!}
            {!! Form::select('lang', $languages, isset($lang) ? $lang : null, ['class' => 'form-control select2 languages-vendor']); !!}
        @endif
    </div>
	<div class="clearfix"></div>

	<div class="form-group">
		{!! Form::label('thumbnail', 'Featured Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($vendor) && !empty($vendor->thumbnail))
		  			<img src="{{$vendor->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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

	<div class="clearfix"></div>
	<div class="form-group">
		@if(isset($vendor))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>


{!! Form::close() !!}

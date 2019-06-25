@if(isset($member))
	{!! Form::model($member, ['action' => ['Admin\CustomPagesController@teamUpdate', $member->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $member->id) !!}
@else
	{!! Form::open(['action' => 'Admin\CustomPagesController@teamStore', 'enctype' => "multipart/form-data"]) !!}
	@if(isset($parent_lang_id))
		{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
	@endif
@endif
<div class="col-md-12">
	<div class="form-group">
		{!! Form::label('title', 'Name') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('position', 'Position'); !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('position', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="col-md-6 no-padding-left">
		<div class="form-group">
			{!! Form::label('category_id', 'Category'); !!}
			<div class='input-group'>
			    <span class="input-group-addon">
			        <span class="fa fa-key"></span>
			    </span>
			    @if(isset($member) && isset($member->category_id))
			    	@include('admin.layouts.parts.categories_dropdown', ['category_id' => $member->category_id])
			    @else
			    	@include('admin.layouts.parts.categories_dropdown')
			    @endif
			</div>
			<div class="clearfix"></div>
			<a href="#" class="pull-right" id="add-category" data-type="team"><i class="fa fa-plus"></i> Add New Category</a>
		</div>
	</div>
	<div class="form-group col-md-6 no-padding-right">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>
	<div class="clearfix"></div>

	<div class="form-group col-md-6 no-padding-left">
		{!! Form::label('lang', 'Language'); !!}
		@if(isset($parent_lang_id) || (isset($member) && $member->lang == 'arm'))
			{!! Form::select('lang', ['arm' => 'Հայերեն'], null, ['class' => 'form-control select2', 'readonly']); !!}
		@else
			{!! Form::select('lang', ['en' => 'English'], null, ['class' => 'form-control select2', 'readonly']); !!}
		@endif
	</div>
	<div class="col-md-6 no-padding-right">
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
	<div class="form-group col-md-6 no-padding">
		{!! Form::label('thumbnail', 'Member Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($member) && !empty($member->thumbnail))
		  			<img src="{{$member->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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
	<div class="clearfix"></div>

	<hr>
	<div class="form-group">
		@if(isset($member))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>

		
{!! Form::close() !!}
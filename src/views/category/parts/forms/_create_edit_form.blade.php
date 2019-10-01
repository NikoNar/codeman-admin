@if(isset($category)  && !isset($parent_lang_id))
	{!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $category->id) !!}
@elseif(isset($category) && isset($parent_lang_id) )
	{!! Form::model($category, ['route' => 'categories.store', 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'category-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
	{!! Form::open(['route' => 'categories.store', 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif
{!! Form::hidden('type', $type) !!}
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

	{!! Form::label('content', 'Description'); !!}
	{!! Form::textarea('content', null, ['class' => 'form-control ckeditor']); !!}
	<div class="">
		@include('admin-panel::layouts.parts._parent_category')
	</div>

	<div class="clearfix"></div>
	<br>
</div>
<div class="col-md-3">
	<div class="form-group">
		{!! Form::label('created_at', 'Created'); !!}
		<div class="clearfix"></div>
		@include('admin-panel::components.timestamps',['value'=>isset($category)?$category->created_at : null, 'updated'=>isset($category)?$category->updated_at->diffForHumans() : null ])
		<div class="clearfix"></div>
	</div>

	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>

	<div class="form-group">
		@if(isset($languages) && !empty($languages))
			@isset($category)
				<input type="hidden" name="resource_id" value="{{$category->id}}">
			@endif
			{!! Form::label('language_id', 'Language'); !!}
			{!! Form::select('language_id', $languages, isset($language_id) ? $language_id : null, ['class' => 'form-control select2 languages', 'data-resource' => isset($module)? $module : '']); !!}
		@endif
	</div>
	<div class="form-group">
		{!! Form::label('thumbnail', 'Featured Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($category) && !empty($category->thumbnail))
					<img src="{{$category->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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
		@if(isset($category))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>


{!! Form::close() !!}

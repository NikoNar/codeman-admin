
@if(isset($resource)  && !isset($parent_lang_id))
	{!! Form::model($resource, ['url' => ["admin/resource/$module/update/$resource->id", ], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $resource->id) !!}
@elseif(isset($resource) && isset($parent_lang_id) )
	{!! Form::model($resource, ['url' => "admin/resource/$module/store", 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'resource-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
	{!! Form::open(['url' => "admin/resource/$module/store", 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif
{!! Form::hidden('type', $module) !!}
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
	@if(in_array('slug', $options))
		<div class="form-group">
			<div class="col-md-12 no-padding">
				{!! Form::label('slug', 'Slug'); !!}
				<div class='input-group'>
					<span class="input-group-addon">
						@if(isset($resource))
							<a href="{!! $resource->permalink !!}" target="_blank">
								<i class="fa fa-link"></i>
							</a>
						@else
							<span class="fa fa-link"></span>
						@endif
					</span>
					<span class="input-group-addon no-border-right">
						<i>
							@if(isset($resource))
								{{ $resource->permalink_excerpt_slug .'/' }}
							@else
								{{ URL::to($module).'/' }}
							@endif
						</i>
					</span>
					@if(isset($slugEdit) && $slugEdit == false)
						{!! Form::text('slug', null, ['class' => 'form-control', 'readonly']) !!}
					@else
						{!! Form::text('slug', null, ['class' => 'form-control']) !!}
					@endif
				</div>
			</div>
		</div>
	@endif
	
	<div class="clearfix"></div>
	<br>

	@if(in_array('ckeditor', $options))
		@include('admin-panel::components.ckeditor')
	@endif
	
	@if(in_array('content_builder', $options))
		@include('admin-panel::page.content-builder.index')
	@endif
	
	@include('admin-panel::components.additional_options')
	<div class="clearfix"></div>
	
	@if($relations)
		<div class="panel-group" id="accordion">
			<div class="form-group">
				@isset($resource)
					@php
						$current_rel = $resource->relations->groupBy('type')->toArray();
					@endphp
					{!! Form::label('relations', 'Relations'); !!}
					@foreach($relations as $key => $val)
						@include('admin-panel::components.relations', ['relation_name'=> $key, 'items' => $val, 'attached_relations' => @isset($attached_relations)? $attached_relations:null])
					@endforeach
					{!! Form::hidden('relations', null, ['id' => 'relations']) !!}
				@else
					{!! Form::label('relations', 'Relations'); !!}
					@foreach($relations as $key => $val)
						@include('admin-panel::components.relations', ['relation_name'=> $key, 'items' => $val])
					@endforeach
					{!! Form::hidden('relations', null, ['id' => 'relations']) !!}
				@endif
			</div>
		</div>
	@endif
	
	@include('admin-panel::components.seo_block')
	
</div>
<div class="col-md-3">
	<div class="form-group">
		{!! Form::label('created_at', 'Created'); !!}
		@include('admin-panel::components.timestamps',[
			'value' => isset($resource)?$resource->created_at : date('Y-m-d H:i:a'), 
			'updated' => isset($resource)?$resource->updated_at->diffForHumans() : null ])
		<div class="clearfix"></div>
	</div>

	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>

	@if(in_array('languages', $options))
		@include('admin-panel::components.languages')
	@endif

	@if(in_array('categories', $options))
		@include('admin-panel::components.categories')
	@endif

	@if(in_array('thumbnail', $options))
		@include('admin-panel::components.thumbnail', [
			'name' => 'thumbnail',
			'attributes' => isset($val['attributes']) ? $val['attributes'] : []
		])
	@endif

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
		@if(isset($resource))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>

{!! Form::close() !!}

@if(isset($resource)  && !isset($parent_lang_id))
	{!! Form::model($resource, ['url' => ["admin/$module/$resource->id", ], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $resource->id) !!}

@elseif(isset($resource) && isset($parent_lang_id) )
	{!! Form::model($resource, ['url' => "admin/$module", 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'resource-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}

@else
	{!! Form::open(['url' => "admin/$module", 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
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
	@if(in_array('slug', $options))
		<div class="form-group">
			<div class="col-md-12 no-padding">
				{!! Form::label('slug', 'Slug'); !!}
				<div class='input-group'>
					<span class="input-group-addon">

						@if(isset($resource))
							<a href="{!! url($module.'/'.$resource->slug) !!}" target="_blank">
								<i class="fa fa-link"></i>
							</a>
						@else
							<span class="fa fa-link"></span>
						@endif
					</span>
					<span class="input-group-addon no-border-right">
						<i>
							@if(isset($resource))
								{{ URL::to('/'.buildUrl($resource, array(), false)).'/'.$module }}
							@else
								{{ URL::to('/') }}
							@endif
						/</i>
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

	@if(isset($additional_options) && !empty($additional_options))
		@foreach($additional_options as $key => $val)
			@if($val['type'] != '' && ((isset($val['location']) && $val['location'] == 'default') || !isset($val['location']) ))
				@switch($val['type'])
					@case('select')
						@include('admin-panel::components.select', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'], 'options'=>explode(',', $val['type_options']), 'multiple'=>array_key_exists('multiple', $val)])
					@break
					@case('textarea')
						@include('admin-panel::components.textarea', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('image')
						@include('admin-panel::components.thumbnail', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('gallery')
						@include('admin-panel::components.gallery', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('editor')
						@include('admin-panel::components.ckeditor', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('datepicker')
						@include('admin-panel::components.datepicker', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('iconpicker')
					@include('admin-panel::components.iconpicker', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@default
						@include('admin-panel::components.input', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'], 'options'=>explode(',', $val['type_options'])])
					@break
				@endswitch
			@endif
		@endforeach
	@endif

	<div class="clearfix"></div>
	<div class="box">
	    <div class="box-header with-border">
	        <h3 class="box-title">SEO Options</h3>
	    </div>
	    <div class="box-body">
	    	<div class="form-group">
	    		{!! Form::label('meta_title', 'Meta Title'); !!}
	    		{!! Form::text('meta_title', null, ['class' => 'form-control', 'placeholder' => '%title% | %sitename%']) !!}
	    	</div>
	    	<div class="form-group">
	    		{!! Form::label('meta_description', 'Meta Description'); !!}
	    		{!! Form::text('meta_description', null, ['class' => 'form-control']) !!}
	    	</div>
	    	<div class="form-group">
	    		{!! Form::label('meta_keywords', 'Meta Keywords'); !!}
	    		{!! Form::text('meta_keywords', null, ['class' => 'form-control', 'data-role' => "tagsinput" ]) !!}
	    	</div>
	    </div>
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		{!! Form::label('created_at', 'Created Date'); !!}
		@include('admin-panel::components.timestamps',[
			'value' => isset($resource)?$resource->created_at : date('Y-m-d H:i:a'), 
			'updated' => isset($resource)?$resource->updated_at->diffForHumans() : null 
		])

		<div class="clearfix"></div>
	</div>

	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>


	@if(in_array('languages', $options))
		@include('admin-panel::components.languages', ['module' => ''])
	@endif

	@if(in_array('categories', $options))
		@include('admin-panel::components.categories')
	@endif


	@if(in_array('thumbnail', $options))
		@include('admin-panel::components.thumbnail')
	@endif
	
	@if(isset($additional_options) && !empty($additional_options))
		@foreach($additional_options as $key => $val)
			@if($val['type'] != '' && isset($val['location']) && $val['location'] == 'righ-sidebar')
				@switch($val['type'])
					@case('select')
						@include('admin-panel::components.select', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'], 'options'=>explode(',', $val['type_options']), 'multiple'=>array_key_exists('multiple', $val)])
					@break
					@case('textarea')
						@include('admin-panel::components.textarea', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('image')
						@include('admin-panel::components.thumbnail', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
						
					@break
					@case('gallery')
						@include('admin-panel::components.gallery', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('editor')
						@include('admin-panel::components.ckeditor', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('datepicker')
						@include('admin-panel::components.datepicker', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@case('iconpicker')
					@include('admin-panel::components.iconpicker', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type']])
					@break
					@default
						@include('admin-panel::components.input', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'], 'options'=>explode(',', $val['type_options'])])
					@break
				@endswitch
			@endif
		@endforeach
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
	<br>

	@if(isset($relations) && !empty($relations))
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

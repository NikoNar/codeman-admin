@if(isset($resource)  && !isset($parent_lang_id))
	{!! Form::model($resource, ['url' => ["admin/marketing/$module/$resource->id", ], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $resource->id) !!}

@elseif(isset($resource) && isset($parent_lang_id) )
	{!! Form::model($resource, ['url' => "admin/marketing/$module", 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'resource-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}

@else
	{!! Form::open(['url' => "admin/marketing/", 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif

<div class="col-md-8 border-right">
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="general">
			<div class="form-group">
				{!! Form::label('code', 'Coupone Code') !!}
				<div class='input-group'>
					<span class="input-group-addon">
						<span class="fa fa-barcode"></span>
					</span>
					{!! Form::text('code', null, ['class' => 'form-control']) !!}
					<span class="input-group-btn">
						<button type="button" id="generate-coupon-code" class="btn btn- btn-flat btn-primary">Generate Code</button>
					</span>
				</div>
			</div>
			@include('admin-panel::components.additional_options', [
				'location' => 'default',
				'tab' => 'general'
			])
		</div>
		<div role="tabpanel" class="tab-pane" id="restrictions">
			@include('admin-panel::components.additional_options', [
				'location' => 'default',
				'tab' => 'restrictions'
			])
		</div>
		<div role="tabpanel" class="tab-pane" id="limitations">
			@if(isset($relations) && !empty($relations))
				@foreach($relations as $relation_name => $relation)
					<div class="form-group">
					<label for="relation-{{ $relation_name }}">{{ ucwords($relation_name) }}</label>
						{{ Form::select($relation_name.'[]', $relation, null, ['class' => 'form-control select2 select2-checkbox', 'multiple' => true])	}}
						<small class="form-text text-muted"></small>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		{!! Form::label('created_at', 'Created') !!}
		@include('admin-panel::components.timestamps', [
			'value' => isset($resource)?$resource->created_at : date('Y-m-d H:i:a'), 
			'updated' => isset($resource)?$resource->updated_at->diffForHumans() : null 
		])
	</div>
	<hr>
	@include('admin-panel::components.status')
	
	{{-- Including recourec meta options --}}
	@include('admin-panel::components.additional_options', ['location' => 'right-sidebar'])

	<div class="clearfix"></div>

	<div class="form-group form_submit_btn">
		<hr>	
		@if(isset($resource))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']) !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']) !!}
		@endif
	</div>
</div>

{!! Form::close() !!}

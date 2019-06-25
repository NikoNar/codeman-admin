<div class="form-group">
	{!! Form::label('meta[banner][text]', 'Banner'); !!}
	{!! Form::textarea('meta[banner][text]', null, ['class' => 'form-control', 'id'=>'banner']); !!}

	<div class="form-group">
		{!! Form::label('meta[banner][bg]', 'Background Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($page->meta['banner']['bg']) && !empty($page->meta['banner']['bg']))
		  			<img src="{{$page->meta['banner']['bg']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
				@else
		  			<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
				@endif
		  	</div>
		  	<div>
		    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
		    		<span class="fileupload-new">Select image</span>
					{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
					{!! Form::hidden('meta[banner][bg]', null, ['id' => 'thumbnail']); !!}
				</span>
		    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
		  		<div class="clearfix"></div>
		  	</div>
		</div>
	</div>		
</div>



<div class="form-group row">
	<div class="col-md-3">
		{!! Form::label('meta[years]', 'Activity'); !!}
		{!! Form::text('meta[years]', null, ['class' => 'form-control']); !!}	
	</div>
	<div class="col-md-3">
		{!! Form::label('meta[transactions]', 'Transactions Made'); !!}
		{!! Form::text('meta[transactions]', null, ['class' => 'form-control']); !!}	
	</div>
	<div class="col-md-3">
		{!! Form::label('meta[staff]', 'Staff'); !!}
		{!! Form::text('meta[staff]', null, ['class' => 'form-control']); !!}	
	</div>
	<div class="col-md-3">
		{!! Form::label('meta[partners]', 'Partners'); !!}
		{!! Form::text('meta[partners]', null, ['class' => 'form-control']); !!}	
	</div>	
</div>
<div class="form-group">
	{!! Form::label('meta[principles]', 'Main Principles'); !!}
	{!! Form::textarea('meta[principles]', null, ['class' => 'form-control', 'id'=>'principles']); !!}		
</div>
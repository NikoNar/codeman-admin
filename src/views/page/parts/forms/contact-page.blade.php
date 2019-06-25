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

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Contact Details</h3>
    </div>
    <div class="box-body">
	    <div class="col-md-4 no-padding">
	    	<div class="form-group">
	    		{!! Form::label('meta[address]', 'Address'); !!}
	    		<div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-phone"></span>
	    		    </span>

	    			{!! Form::text('meta[address]', null, ['class' => 'form-control']) !!}
	    		</div>
	    	</div>
	    </div>
		<div class="col-md-2 no-padding">
	    	<div class="form-group">
	    		{!! Form::label('meta[contact_phone_number]', 'Phone'); !!}
	    		<div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-phone"></span>
	    		    </span>

	    			{!! Form::text('meta[contact_phone_number]', null, ['class' => 'form-control']) !!}
	    		</div>
	    	</div>
	    </div>
	    <div class="col-md-2 no-padding">
	    	<div class="form-group">
	    		{!! Form::label('meta[contact_phone_number_ceo]', 'Phone CEO'); !!}
	    		<div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-phone"></span>
	    		    </span>
	    			{!! Form::text('meta[contact_phone_number_ceo]', null, ['class' => 'form-control']) !!}
	    		</div>
	    	</div>
	    </div>
	    <div class="col-md-2 no-padding">
			<div class="form-group">
				{!! Form::label('meta[contact_email_address]', 'Email'); !!}
				<div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-envelope"></span>
	    		    </span>
					{!! Form::text('meta[contact_email_address]', null, ['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
		<div class="col-md-2 no-padding">
			<div class="form-group">
				{!! Form::label('meta[contact_email_address_ceo]', 'Email CEO'); !!}
				<div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-envelope"></span>
	    		    </span>
					{!! Form::text('meta[contact_email_address_ceo]', null, ['class' => 'form-control']) !!}
				</div>
			</div>
		</div>
	</div>
</div>

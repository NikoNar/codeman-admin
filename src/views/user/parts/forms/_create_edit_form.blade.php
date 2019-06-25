@if(isset($user)  && !isset($parent_lang_id))
	{!! Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $user->id) !!}
@elseif(isset($user) && isset($parent_lang_id) )
	{!! Form::model($user, ['route' => 'user.store', 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'user-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
	{!! Form::open(['route' => 'user.store', 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif
<div class="col-md-9 border-right">
	<div class="form-group">
		{!! Form::label('name', 'Name') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('email', 'Email') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-at"></span>
		    </span>
			{!! Form::text('email', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('password', 'Password') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-key"></span>
		    </span>
			{!! Form::text('password', null, ['class' => 'form-control']) !!}
			<span class="input-group-addon" style="padding: 0">
		        <button type="button"  id="generate-password" class="btn btn-danger btn-sm btn-flat" style="height: 32px;">Generate Password</button>
		    </span>
		</div>
	</div>
	
	<div class="clearfix"></div>
	<br>
	
	<!-- <div class="form-group">

		{!! Form::label('description', 'Content'); !!}
		{!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}
	</div> -->

	<!-- <div class="form-group">
		{!! Form::label('content', 'About'); !!}
		{!! Form::textarea('content', null, ['class' => 'form-control',  'name' =>  'content']); !!}	
	</div> -->
	<div class="clearfix"></div>
<!-- 	<div class="box">
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
	    		{!! Form::text('meta-keywords', null, ['class' => 'form-control', 'data-user' => "tagsinput" ]) !!}
	    	</div>
	    </div>
	</div> -->
</div>
<div class="col-md-3">
 	

 	<div class="form-group">
 		{!! Form::label('role', 'Role') !!}
 		<div class='input-group'>
 		    <span class="input-group-addon">
 		        <span class="fa fa-user"></span>
 		    </span>
 			{!! Form::text('role', null, ['class' => 'form-control']) !!}
 		</div>
 	</div>

	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['active' => 'Active', 'disabled' => 'Disabled'], null, ['class' => 'form-control select2']); !!}
	</div>
	

	<!-- <div class="form-group">
		{!! Form::label('thumbnail', 'Featured Image'); !!}
		<div class="fileupload fileupload-new" data-provides="fileupload">
			<div class="fileupload-preview thumbnail" style="width: 100%;">
				@if(isset($user) && !empty($user->thumbnail))
		  			<img src="{{$user->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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
	</div> -->

	<hr>
	<div class="form-group">
		@if(isset($user))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>

		
{!! Form::close() !!}

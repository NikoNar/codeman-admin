<div class="form-group">
    {!! Form::label('thumbnail', 'Featured Image'); !!}
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-preview thumbnail" style="width: 100%;">
            @if(isset($resource) && !empty($resource->thumbnail))
            <img src="{{$resource->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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

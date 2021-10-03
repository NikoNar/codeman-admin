<div class="form-group">
    {!! Form::label($id, $name); !!}
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-preview thumbnail" style="width: 100%;">
            @if(isset($resource->meta[$name]) && !empty($resource->meta[$name]))
            <img src="{{$resource->meta[$name]}}" class="img-responsive thumbnail-image" alt="" onerror="imgError(this);" id="">
            @else
            <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive thumbnail-image" alt="No Featured Image" onerror="imgError(this);" id="">
            @endif
        </div>
        <div>
                <span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
                    <span class="fileupload-new">Select image</span>
                    {{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
                    {!! Form::hidden("meta[$name]", null, ['class' => 'thumbnail']); !!}
                </span>
            <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload" >Remove</a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>



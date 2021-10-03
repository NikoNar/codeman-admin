<div class="form-group">
    {!! Form::label('thumbnail', isset($label) ? $label : 'Featured Image') !!}
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-preview thumbnail" style="width: 100%;">

            @if(isset($name) && isset($resource[$name]))
                <img src="{{$resource[$name]}}" class="img-responsive thumbnail-image" alt="" onerror="imgError(this);">
            @elseif(isset($name))
                <img src="{{ isset($value) ? $value : '' }}" class="img-responsive thumbnail-image" alt="" onerror="imgError(this);">
            @elseif(isset($resource) && !empty($resource->thumbnail))
                <img src="{{$resource->thumbnail}}" class="img-responsive thumbnail-image" alt="" onerror="imgError(this);">
            @else
                <img src="{{ asset('admin-panel/images/no-image.jpg') }}" class="img-responsive thumbnail-image" alt="No Featured Image" onerror="imgError(this);">
            @endif
        </div>
        <div>
            <span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
                <span class="fileupload-new">Select image</span>
                @if(isset($name) && isset($resource[$name]))
                    {{ Form::hidden($name, $resource[$name], ['class' => 'thumbnail']+$attributes) }}
                @elseif(isset($name))
                    {{ Form::hidden($name, isset($value) ? $value : null, ['class' => 'thumbnail']+$attributes) }}
                @elseif(isset($resource))
                    {{ Form::hidden('thumbnail', $resource->thumbnail, ['class' => 'thumbnail']+$attributes) }}
                @endif
            </span>
            <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload">Remove</a>
            <div class="clearfix"></div>
        </div>
    </div>
    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>

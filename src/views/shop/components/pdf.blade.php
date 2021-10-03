<div class="form-group">
    {!! Form::label('file', 'File'); !!}
    <div class="fileupload fileupload-new" data-provides="fileupload">
        {{--            <div class="fileupload-preview thumbnail" style="width: 100%;">--}}
        {{--                @if(isset($page->file) && !empty($page->file))--}}
        {{--                    <img src="{{$page->meta['banner']['bg']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">--}}
        {{--                @else--}}
        {{--                    <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">--}}
        {{--                @endif--}}
        {{--            </div>--}}
        <div>
		    	<span class="btn btn-file btn-primary btn-flat col-md-3 media-open pdf">
		    		<span class="fileupload-new">Select File</span>
					{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
                    {{--                    {!! Form::hidden('file', null, ['id' => 'thumbnail']); !!}--}}
				</span>
            <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-3" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-6" style="padding-left:0; padding-right: 0;">
            {!! Form::text('file', null, ['id' => 'thumbnail', 'class' => 'form-control']); !!}
        </div>
    </div>
</div>
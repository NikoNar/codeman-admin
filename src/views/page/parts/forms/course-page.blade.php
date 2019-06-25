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


<div class="form-group">
    {!! Form::label('content', 'Course Advantages'); !!}
    {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'content', 'name' =>  'content']); !!}
</div>


<div class="form-group">
    {!! Form::label('meta[course][text][text]', 'Course Structure'); !!}
    {!! Form::textarea('meta[course][text]', null, ['class' => 'form-control', 'id'=>'courses']); !!}
    <div class="form-group">
        {!! Form::label('meta[course][bg]', 'Background Image'); !!}
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-preview thumbnail" style="width: 100%;">
                @if(isset($page->meta['course']['bg']) && !empty($page->meta['course']['bg']))
                    <img src="{{$page->meta['course']['bg']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
                @else
                    <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                @endif
            </div>
            <div>
		    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
		    		<span class="fileupload-new">Select image</span>
					{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
                    {!! Form::hidden('meta[course][bg]', null, ['id' => 'thumbnail']); !!}
				</span>
                <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Course Details</h3>
    </div>
    <div class="box-body">
        <div class="col-md-3 no-padding">
            <div class="form-group">
                {!! Form::label('meta[price]', 'Price'); !!}
                <div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fas fa-dollar-sign"></span>
	    		    </span>

                    {!! Form::text('meta[price]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-3 no-padding">
            <div class="form-group">
                {!! Form::label('meta[duration]', 'Duration'); !!}
                <div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-calendar-alt"></span>
	    		    </span>

                    {!! Form::text('meta[duration]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-3 no-padding">
            <div class="form-group">
                {!! Form::label('meta[group]', 'Group members'); !!}
                <div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-group"></span>
	    		    </span>
                    {!! Form::text('meta[group]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-3 no-padding">
            <div class="form-group">
                {!! Form::label('meta[start]', 'Start'); !!}
                <div class='input-group'>
	    		    <span class="input-group-addon">
	    		        <span class="fa fa-calendar-check"></span>
	    		    </span>
                    {!! Form::text('meta[start]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

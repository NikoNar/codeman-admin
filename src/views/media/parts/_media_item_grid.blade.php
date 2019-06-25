
<div class="item" data-id="{!! $image->filename !!}">
	
	{{-- <button type="button" class="close delete-file" aria-label="Close">
		<i class="fa fa-trash delete-file-icon"></i>
	</button> --}}
	<div class="actions">
		<button class="btn btn-sm btn-primary btn-flat details" type="button" >
			<i class="fa fa-info-circle"></i> Details
		</button>
		<button class="btn btn-sm btn-danger btn-flat delete-file" type="button" >
			<i class="fa fa-trash"></i> Delete
		</button>
	</div>
	@if(!is_url($image->filename))
		<img src="{!! url('/media/icon_size').'/'.$image->filename !!}" alt="" class="thumbnail img-responsive">
	@else
		<img src="{!! $image->filename !!}" alt="" class="thumbnail img-responsive">
	@endif
	{{-- <span class="filename">
		{!! $image->original_name !!}
	</span> --}}
	<input type="hidden" name="filename" value="{!! $image->original_name !!}">
	<input type="hidden" name="alt" value="{!! $image->alt !!}">
	<input type="hidden" name="width" value="{!! $image->width !!}">
	<input type="hidden" name="height" value="{!! $image->height !!}">
	<input type="hidden" name="file_size" value="{!! $image->file_size !!}">
	<input type="hidden" name="file_type" value="{!! $image->file_type !!}">
	@if(!is_url($image->filename))
		<input type="hidden" name="full-size-url" value="{!! url('/media/full_size').'/'.$image->filename !!}">
	@else
		<input type="hidden" name="full-size-url" value="{!! $image->filename !!}">
	@endif

	<input type="hidden" name="created_at" value="{!! date('F d, Y @ H:i', strtotime($image->created_at)) !!}">
</div>
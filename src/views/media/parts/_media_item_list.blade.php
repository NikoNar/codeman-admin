<tr  data-id="{!! $image->filename !!}">
	<td><input type="checkbox" name="checked" value="{{ $image->id }}"></td>
	<td>
		<div><img src="{!! url('/media/icon_size').'/'.$image->filename !!}" alt="" class="thumbnail img-responsive img-xs"></div>
	</td>
	<td>{!! $image->original_name !!}</td>
	<td>{!! $image->file_size !!}</td>
	<td>{!! $image->file_type !!}</td>

	<td>{!! date('F d, Y @ H:i', strtotime($image->created_at)) !!}</td>
	<td>
		<div class="actions">
			<button class="btn btn-sm btn-primary btn-flat details" type="button" >
				<i class="fa fa-info-circle"></i> Details
			</button>
			<button class="btn btn-sm btn-danger btn-flat delete-file" type="button" >
				<i class="fa fa-trash"></i> Delete
			</button>
		</div>
	</td>
</tr>
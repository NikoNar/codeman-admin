<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td>
		<a href="javascript:void(0)" class="featured-img-change" style="width: 100px">
			<img src="{!! url('images/users/'.$item->profile_pic) !!}" class="thumbnail img-xs " width="100">
			
			<input name="thumbnail" type="hidden" value="">

		</a>
		
		{{ $item->name }}</td>
	
	<td>{{ $item->email }}</td>

		
	<td>
		@foreach($item->roles as $role)
			{{ $role->title.' ' }}
		@endforeach
	</td>
	<td class="text_capitalize">{{ date('M d Y H:i A', strtotime($item->email_verified_at)) }}</td>
	<td>{{ date('M d Y', strtotime($item->created_at)) }}</td>
	<td class="action">
		<a href="{{ route('user.edit', $item->id ) }}" title="Edit" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
		<a href="{{ route('user.destroy', $item->id ) }}" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
	</td>
</tr>
<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td> {{ ++$key }}</td>
	<td> {{ $item->email }}</td>
	<td class="text_capitalize">{{ $item->status }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->created_at)) }}</td>

	<td class="action text-center">
		<a href="{{ route("subscriber-destroy", $item->id ) }}" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
	</td>
</tr>
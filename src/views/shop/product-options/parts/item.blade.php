<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td>
		{{ $item->name }}
	</td>
	<td>
		{{ $item->type }}
	</td>
	<td>
		{{ count($item->productOptions) }}
	</td>
	<td class="text_capitalize">{!! $item->status == 'published' ?
		'<span class="label label-success">Published</span>' : 
		'<span class="label label-danger">Draft</span>' 
	!!}</td>
	<td class="text_capitalize">{!! $item->show_on_website ?
		'<span class="label label-success">Yes</span>' : 
		'<span class="label label-danger">No</span>'
	 !!}</td>
	<td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->updated_at)) }}</td>

	<td class="action">
		<a href="{{ route($module.'.edit', [$item->id] ) }}" title="Edit" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>

		{!! Form::model($item, ['url' => route($module.'.destroy',[$item->id]), 'enctype' => "multipart/form-data", 'method' => 'DELETE', 'class' => 'inline']) !!}
		    <button title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i> Remove</button>
		{!! Form::close() !!}
	</td>
</tr>
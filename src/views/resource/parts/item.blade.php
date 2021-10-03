<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td>
		<a href="javascript:void(0)" class="featured-img-change media-open">
			<img src="{!! $item->thumbnail !!}" class="thumbnail img-xs ">
			<i class="fa fa-camera"></i>
			<input name="thumbnail" type="hidden" value="">
		</a>
		{{ $item->title }}
	</td>
	<td>
		@if(isset($item->categories))
			@foreach($item->categories as $category)
				<span class="label label-success">{{$category->title}}</span>
			@endforeach
		@endif	
	</td>
	<td class="text_capitalize">{{ $item->status }}</td>
	<td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->updated_at)) }}</td>

	<td class="action">
		<a href="{{ route('resources.edit', [$module, $item->id] ) }}" title="Edit" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
			<i class="fa fa-edit"></i>
		</a>
		<a href="{{ route('resources.duplicate', [$module, $item->id] ) }}" title="Duplicate" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Duplicate">
			<i class="fa fa-layer-group"></i>
		</a>

		<a href="{{ route('resources.destroy',[$module, $item->id]) }}" title="Delete" class="btn btn-xs btn-danger confirm-del" data-toggle="tooltip" title="Delete">
			<i class="fa fa-trash"></i>
		</a>
	</td>
</tr>
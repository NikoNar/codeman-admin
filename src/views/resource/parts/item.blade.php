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
		<a href="{{ route('resources.edit', [$module, $item->id] ) }}" title="Edit" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
{{--		<a href="{{ route('resource-translate', $item->id ) }}" title="Translate To Armenian" class="btn btn-xs btn-primary"><i class="fa fa-language"></i></a>--}}
		<a href="{{ route('resources.destroy',[$module, $item->id]) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>
	</td>
</tr>
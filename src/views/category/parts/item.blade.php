<tr data-id="{{ $category->id }}" data-order="{!! $category->order !!}" data-position="{!! $key !!}">
	<td><input type="checkbox" name="checked" value="{{ $category->id }}"></td>
	<td>
		<a href="javascript:void(0)" class="featured-img-change media-open">
			<img src="{!! $category->thumbnail !!}" class="thumbnail img-xs ">
			<i class="fa fa-camera"></i>
			<input name="thumbnail" type="hidden" value="">
		</a>
		{{ $category->title }}
	</td>
	<td> {{ $category->status }} </td>
	<td> {{ $category->type }} </td>
	{{--	<td> {{ $category->type }} </td>--}}
	<td>{{ date('m/d/Y g:i A', strtotime($category->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($category->updated_at)) }}</td>
	<td class="action">
		{{--		<a href="#" title="Edit" data-id="{{ $category->id }}" data-type="{{ $type }}" class="btn btn-xs btn-warning edit-category"><i class="fa fa-edit"></i></a>--}}
		<a href="{{ route('categories.edit', [$category->id, $category->type]) }}"   class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
		<a href="{{ route('categories.destroy', $category->id ) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>
	</td>
</tr>

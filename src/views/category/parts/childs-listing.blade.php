{{-- @foreach($childs as $category)--}}
{{--	<tr data-id="{!! $category->id !!}" >--}}
{{--		<td><input type="checkbox" name="checked" value="{{ $category->id }}"></td>--}}
{{--		<td>--}}
{{--			@for($i = 0; $i <= $level; $i++)--}}
{{--				{{'-----'}}--}}
{{--			@endfor--}}
{{--				<a href="javascript:void(0)" class="featured-img-change media-open">--}}
{{--					<img src="{!! $category->thumbnail !!}" class="thumbnail img-xs ">--}}
{{--					<i class="fa fa-camera"></i>--}}
{{--					<input name="thumbnail" type="hidden" value="">--}}
{{--				</a>--}}
{{--			{{$category->title }} </td>--}}
{{--		<td> {{ $category->content }} </td>--}}
{{--		<td> {{ $category->type }} </td>--}}
{{--		<td> {{ $category->slug }} </td>--}}
{{--		<td>{{ date('m/d/Y g:i A', strtotime($category->created_at)) }}</td>--}}
{{--		<td>{{ date('m/d/Y g:i A', strtotime($category->updated_at)) }}</td>--}}
{{--		--}}

{{--		<td class="action">--}}
{{--			<a href="#" title="Edit" data-id="{{ $category->id }}" data-type="{{ $type }}" class="btn btn-xs btn-warning edit-category"><i class="fa fa-edit"></i></a>--}}
{{--			<a href="{{ route('categories.edit', [$category->id, $category->type]) }}"   class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>--}}
{{--			<a href="{{ route('categories.destroy', $category->id ) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>--}}
{{--		</td>--}}
{{--		@if(count($category->childs))--}}
{{--            @include('admin-panel::category.parts.childs-listing',['childs' => $category->childs, 'level' => ++$level])--}}
{{--            @php $level = 0 @endphp--}}
{{--        @endif--}}
{{--	</tr>--}}
{{--@endforeach--}}

 @foreach($childs as $category)
	<tr data-id="{!! $category->id !!}" >
		<td><input type="checkbox" name="checked" value="{{ $category->id }}"></td>
		<td>
			@for($i = 0; $i <= $level; $i++)
				{{'-----'}}
			@endfor
				<a href="javascript:void(0)" class="featured-img-change media-open">
					<img src="{!! $category->thumbnail !!}" class="thumbnail img-xs ">
					<i class="fa fa-camera"></i>
					<input name="thumbnail" type="hidden" value="">
				</a>
			{{$category->title }} </td>
		<td> {{ $category->content }} </td>
		<td> {{ $category->type }} </td>
{{--		<td> {{ $category->slug }} </td>--}}
		<td>{{ date('m/d/Y g:i A', strtotime($category->created_at)) }}</td>
		<td>{{ date('m/d/Y g:i A', strtotime($category->updated_at)) }}</td>


		<td class="action">
{{--			<a href="#" title="Edit" data-id="{{ $category->id }}" data-type="{{ $type }}" class="btn btn-xs btn-warning edit-category"><i class="fa fa-edit"></i></a>--}}
			<a href="{{ route('categories.edit', [$category->id, $category->type]) }}"   class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
			<a href="{{ route('categories.destroy', $category->id ) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>
		</td>
		@if(count($category->catChilds))
{{--			@if($category->title == 'տեխնիկա')--}}
{{--				{{dd($category->childs)}}--}}
{{--			@endif--}}
            @include('admin-panel::category.parts.childs-listing',['childs' => $category->catChilds, 'level' => ++$level])
            @php $level = 0 @endphp
        @endif
	</tr>
@endforeach


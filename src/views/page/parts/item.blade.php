@php
$thumbnail = null;
if($page->thumbnail != ''){
	$thumbnail = str_replace('/full_size/', '/icon_size/', $page->thumbnail);
}
@endphp
<tr data-id="{{ $page->id }}">
	<td><input type="checkbox" name="checked" value="{{ $page->id }}"></td>
	<td>
		{{-- <a href="javascript:void(0)" class="featured-img-change media-open">
			<img src="{!! $thumbnail !!}" class="thumbnail img-xs">

			<i class="fa fa-camera"></i>
			<input name="thumbnail" type="hidden" value="">
		</a> --}}
		{{ $page->title }}
	</td>
	<td>
	<a href="{!! url(buildUrl($page, array())) !!}" target="_blunk"><i class="fa fa-link"></i> {!! url(buildUrl($page, array())) !!}</a></td>
	<td class="text_capitalize">{{ $page->status }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($page->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($page->updated_at)) }}</td>
	<td class="action">
		<a href="{{ route('page-edit', $page->id ) }}" title="Edit" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
{{--		<a href="{{ route('page-translate', [$page->id, $page->language_id ]) }}" title="Add Language" class="btn btn-xs btn-primary"><i class="fa fa-language"></i></a>--}}
{{--		@can('delete', $page)--}}
		<a href="{{ route('page-destroy', $page->id ) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>
{{--		@endcan--}}
	</td>
</tr>
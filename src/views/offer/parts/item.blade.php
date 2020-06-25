@php
$thumbnail = null;
if($item->thumbnail != ''){
	$thumbnail = str_replace('/full_size/', '/icon_size/', $item->thumbnail);
}
@endphp
<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td>
		<a href="javascript:void(0)" class="featured-img-change media-open">
			<img src="{!! $thumbnail !!}" class="thumbnail img-xs ">
			<i class="fa fa-camera"></i>
			<input name="thumbnail" type="hidden" value="">
		</a>
		{{ $item->title }}</td>
	<td>
		<a href="{!! url('/offer', $item->slug ) !!}" target="_blank">
			<i class="fa fa-link"></i>
			{{ URL::to('/offer') }}/{{ $item->slug }}
		</a>
	</td>
	<td class="text_capitalize">{{ $item->status }}</td>
	<td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->updated_at)) }}</td>

	<td class="action">
		<a href="{{ route("offer.edit", $item->id ) }}" title="Edit" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
		{{-- <a href="{{ action("Admin\offersController@translate", $item->id ) }}" title="Translate To Armenian" class="btn btn-xs btn-primary"><i class="fa fa-language"></i></a> --}}
		<a href="{{ route("offer.delete", $item->id ) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>
	</td>
</tr>

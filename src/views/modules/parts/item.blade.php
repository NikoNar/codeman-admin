<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td>
		<a href="javascript:void(0)">
			{!! $item->icon !!}
{{--			<i class="fa fa-camera"></i>--}}

		</a>
		{{ $item->title }}</td>
	<td>
		@php
			if(null ==  $options  = json_decode($item->options)){
               $options = [];
           }
		@endphp
		@foreach($options as $key=>$val)
			{{ $val  }}
			@if(!$loop->last)
				{{','}}
			@endif
		@endforeach
	</td>
	<td class="text_capitalize">{{ $item->status }}</td>
	<td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->updated_at)) }}</td>

	<td class="action">
		<a href="{{ route('modules.edit', $item->id ) }}" title="Edit" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
		<a href="{{ route('modules.delete', $item->id ) }}" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>
	</td>
</tr>
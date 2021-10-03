<tr data-id="{{ $item->id }}">
	<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
	<td>
		<a href="javascript:void(0)" class="{{-- featured-img-change media-open --}}">
			<img src="{!! $item->logo !!}" class="thumbnail img-xs ">
			{{-- <i class="fa fa-camera"></i> --}}
			{{-- <input name="logo" type="hidden" value=""> --}}
		</a>
		{{ $item->title }}
	</td>
	<td class="text_capitalize">
		{{ $item->lang }}	
	</td>
	<td class="text_capitalize">{{ $item->status }}</td>
	<td>{{ date('m/d/Y', strtotime($item->created_at)) }}</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->updated_at)) }}</td>

	<td class="action">
		<a href="{!! url($module.'/'.$item->slug) !!}" title="View" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-eye"></i> </a>
		<a href="{{ route($module.'.edit', [$item->id] ) }}" title="Edit" class="btn btn-xs btn-warning">
			<i class="fa fa-edit"></i>  
		</a>
		{!! Form::model($item, ['url' => route($module.'.destroy',[$item->id]), 'enctype' => "multipart/form-data", 'method' => 'DELETE', 'class' => 'inline']) !!}
		    <button title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i> Remove</button>
		{!! Form::close() !!}
	</td>
</tr>
@foreach($childs as $child)
	<option value="{{ $child->id }}" @if(isset($category_id) && $category_id == $child->id || isset($selected) && is_array($selected) && in_array($child->id, $selected)) {!! 'selected' !!} @endif>
		@for($i = 0; $i <= $level; $i++)
			{{'---'}}
		@endfor
	    {{ $child->title }}
		@if(count($child->catChilds))
			{{-- @if(isset($category))
            	@include('admin-panel::layouts.parts._category_child',['childs' => $child->catChilds($category->id), 'level' => ++$level])
			@else
			@endif --}}
            	@include('admin-panel::layouts.parts._category_child',['childs' => $child->childs, 'level' => ++$level])
            @php $level = 0 @endphp	

        @endif
	</option>
@endforeach

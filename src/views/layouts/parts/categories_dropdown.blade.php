@if(isset($multiple))
    <select name="category_id[]" id="category" class="select2 form-control" {!! $multiple !!}>
@else
    <select name="category_id" id="category" class="select2 form-control" >
    <option value="0">Choose Category</option>
@endif
    @if(isset($categories) && !empty($categories))
        @foreach($categories as $category)
            @if(!$category->parent_id)
            <option value="{{ $category->id }}" @if(isset($category_id) && $category_id == $category->id || isset($selected) && is_array($selected) && in_array($category->id, $selected)) {!! 'selected' !!} @endif>
                {{ $category->title }}
                @if(count($category->catChilds))
                    @include('admin-panel::layouts.parts._category_child', ['childs' => $category->catChilds, 'level' => 0])
                @endif
            </option>
                @endif
        @endforeach
    @endif
</select>

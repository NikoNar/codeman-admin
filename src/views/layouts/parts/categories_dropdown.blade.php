@if(isset($multiple))
    <select name="category_id[]" id="category" class="select2 form-control" {!! $multiple !!} data-placeholder="Categories">
@else
    <select name="category_id" id="category" class="select2 form-control" data-placeholder="Categories">
        <option value="0">Choose Category</option>
@endif
    @if(isset($categories) && !empty($categories))
        @if(isset($category_id) && isset($selected))
            {!! recursCategoriesOptions($categories, 0, $selected, $category_id) !!}
        @elseif(isset($selected))
            {!! recursCategoriesOptions($categories, 0, $selected, null) !!}
        @elseif(isset($category_id))
            {!! recursCategoriesOptions($categories, 0, null, $category_id) !!}
        @else
            {!! recursCategoriesOptions($categories, 0, null, null) !!}
        @endif
    @endif
</select>


{{--@if(isset($multiple))--}}
{{--    <select name="category_id[]" id="category" class="select2 form-control" {!! $multiple !!}>--}}
{{--@else--}}
{{--    <select name="category_id" id="category" class="select2 form-control" >--}}
{{--    <option value="0">Choose Category</option>--}}
{{--@endif--}}
{{--    @if(isset($categories) && !empty($categories))--}}
{{--        @foreach($categories as $category)--}}
{{--            @if(!$category->parent_id)--}}
{{--            <option value="{{ $category->id }}" @if(isset($category_id) && $category_id == $category->id || isset($selected) && is_array($selected) && in_array($category->id, $selected)) {!! 'selected' !!} @endif>--}}
{{--                {{ $category->title }}--}}
{{--                @if(count($category->catChilds))--}}
{{--                    @include('admin-panel::layouts.parts._category_child', ['childs' => $category->catChilds, 'level' => 0])--}}
{{--                @endif--}}
{{--            </option>--}}
{{--                @endif--}}
{{--        @endforeach--}}
{{--    @endif--}}
{{--</select>--}}

@php

    function recurs($result, $parent_id, $selected = null, $category_id = null, &$html = '')
    {
        if(isset($result[$parent_id])):

            foreach ($result[$parent_id] as $key => $category):
                    $is_selected = '';
                    if( (isset($category_id) && $category_id == $category->id) ||
                        (isset($selected) && is_array($selected) && in_array($category->id, $selected))):
                        $is_selected = " selected='selected'";
                    endif;
                    $html .='<option value="'.$category->id.'" '.$is_selected.' >';
                        for($i = 2; $i <= $category->level; $i++):
                            $html .= '---';
                        endfor;
                        $html .= $category->title;
                    $html .='</option>';
                recurs($result, $category->id, $selected, $category_id, $html);
            endforeach;
        endif;
        return $html;
    }
@endphp

@if(isset($multiple))
    <select name="category_id[]" id="category" class="select2 form-control" {!! $multiple !!}>
        @else
            <select name="category_id" id="category" class="select2 form-control" >
                <option value="0">Choose Category</option>
                @endif
                @if(isset($categories) && !empty($categories))
                    @if(isset($category_id) && isset($selected))
                        {!! recurs($categories, 0, $selected, $category_id) !!}
                    @elseif(isset($selected))
                        {!! recurs($categories, 0, $selected, null) !!}
                    @elseif(isset($category_id))
                        {!! recurs($categories, 0, null, $category_id) !!}
                    @else
                        {!! recurs($categories, 0, null, null) !!}
                    @endif
                @endif
            </select>

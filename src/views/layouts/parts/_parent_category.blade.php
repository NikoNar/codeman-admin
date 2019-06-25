{!! Form::label('parent_id', 'Parent Category'); !!}
<div class="form-group">
    {{-- {!! Form::select('parent_id', ['' => 'Please Select'] + $categories ,  null, ['class' => 'form-control select2']) !!} --}}
    <select name="parent_id" id="parent_id" class="form-control select2">
        <option value="0">Choose Category</option>
        @if(isset($categories) && !empty($categories))
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @if(isset($category) && $cat->id == $category->parent_id ) {!! 'selected' !!} @endif>
                    {{ $cat->title }}
                    @if(count($cat->catChilds))
                        @if(isset($category))
                            @include('admin-panel::layouts.parts._category_child',
                            ['childs' => $cat->catChilds, 'level' => 1, 'category_id' => $category->parent_id ])
                        @else
                            @include('admin-panel::layouts.parts._category_child',
                            ['childs' => $cat->catChilds, 'level' => 1 ])
                        @endif
                    @endif
                </option>
            @endforeach
        @endif
    </select>
</div>
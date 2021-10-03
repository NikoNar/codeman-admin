<div class="form-group">
    {!! Form::label('content', isset($label)? $label: 'Content'); !!}
    {!! Form::textarea(isset($name) ? $name : 'content', isset($value) ? $value : null, ['class' => 'form-control ckeditor', 'id' => isset($index)? 'editor-'.$index: 'editor']); !!}
    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>

<div class="form-group">
    @if(isset($value))
    {!! Form::label('content', 'Content'); !!}
    @endif
    {!! Form::textarea(isset($name)? $name: 'content', isset($value)? $value:null, ['class' => 'form-control ckeditor', 'id' => isset($index)? 'editor-'.$index: 'editor']); !!}
</div>

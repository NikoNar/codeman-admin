<div class="form-group">
    <label for="{{$id}}">{{$label}}</label>
    {!! Form::textarea($name, isset($value) ? $value : null, [
    	'class' => 'form-control', 
    	'id' => $id,
    	'rows' => isset($textarea_rows) ? $textarea_rows : 3
    ]); !!}
    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>

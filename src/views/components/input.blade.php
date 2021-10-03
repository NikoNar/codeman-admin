@php $input_type = isset($input_type) ? $input_type : 'text'; @endphp
<div class="form-group">
	@isset($label) <label for="{{ $id }}">{{ $label }}</label> @endif
    {{ Form::$input_type(isset($name) ? $name: null, isset($value) ? $value: null, ['class' => 'form-control']+$attributes) }} 
    
    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>
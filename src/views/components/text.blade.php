<div class="form-group">
	@isset($label) <label for="{{ $id }}">{{ $label }}</label> @endif
    {{ Form::text(isset($name) ? $name: null, isset($value) ? $value: null, ['class' => 'form-control']) }} 

    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
    <div class='input-group date datetimepicker-simple'>
        {{ Form::text(isset($name)? $name : null, isset($value) ? $value : null, ['class' => 'form-control', 'id' => $id]) }}
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>
<div class="form-group">
    <div class='input-group date datetimepicker-simple'>
        <input type='text' class="form-control" name="created_at" value="{{isset($value)? $value : null}}"/>
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
    @isset($updated)
        {!! Form::label('updated_at', 'Last update:',['class'=>'']); !!}
        {{$updated}}
    @endif
</div>

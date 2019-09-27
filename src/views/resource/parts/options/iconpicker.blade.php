<div class="form-group">
    {!! Form::label('icon', 'Icon') !!}
    <div class='input-group'>
        <button class="btn btn-primary" role="iconpicker" name="meta[{{$name}}]" data-icon="{{ isset($resource->meta[$name])? $resource->meta[$name] : null }}" ></button>
    </div>
</div>
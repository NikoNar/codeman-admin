@isset($resource)
    <div class="form-group date">
        <label for="{{$id}}">{{$label}}</label>
        <input type="text" class="form-control datepicker" id="{{$id}}" name="meta[{{$name}}]" value="{{isset($resource->meta[$name])?$resource->meta[$name] : null }}" autocomplite="off">
    </div>
@else
    <div class="form-group date">
        <label for="{{$id}}">{{$label}}</label>
        <input type="text" class="form-control  datepicker" id="{{$id}}" name="meta[{{$name}}]" autocomplete="off">
    </div>
@endif

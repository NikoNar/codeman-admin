@isset($resource)
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        <textarea class="form-control" id="{{$id}}" name="meta[{{$name}}]" cols="50" rows="10" >{{ isset($resource->meta[$name])? $resource->meta[$name] : null }}</textarea>
    </div>
@else
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        <textarea class="form-control" id="{{$id}}" name="meta[{{$name}}]" cols="50" rows="10"></textarea>
    </div>
@endif
@isset($resource)
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        @if($type == 'radio' || $type == 'checkbox')
            <br>
            @foreach($options as $key => $val)
                @php
                    $keyval = explode(':', $val);
                @endphp
                    <input type="{{$type}}" name="meta[{{$name}}][]" value="{{$keyval[1]}}" @if(isset($resource->meta[$name]) && in_array($keyval[1],$resource->meta[$name])) checked @endif >{{$keyval[0]}}<br>
            @endforeach
        @else
{{--            {{dd($type,$name, $resource->meta[$name])}}--}}
            <input type="{{$type}}" name="meta[{{$name}}]" class="form-control" value="{{isset($resource->meta[$name])? $resource->meta[$name] : null}}" id="{{$id}}">
        @endif
    </div>
@else
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        @if($type == 'radio' || $type == 'checkbox')
            <br>
            @foreach($options as $key => $val)
                @php
                    $keyval = explode(':', $val);
                @endphp
                    <input type="{{$type}}" name="meta[{{$name}}][]" value="{{$keyval[1]}}" >{{$keyval[0]}}<br>
            @endforeach
        @else
            <input type="{{$type}}" name="meta[{{$name}}]" class="form-control"  id="{{$id}}">
        @endif
    </div>
@endif


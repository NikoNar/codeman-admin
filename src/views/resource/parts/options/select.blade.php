@isset($resource)
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        <select  @if($multiple) name="meta[{{$name}}][]" multiple @else name="meta[{{$name}}]" @endif id="{{$id}}">
            @php
                if(isset($resource->meta[$name])){
                    $res_val = $resource->meta[$name];
                } else {
                    $res_val = [];
                }
            @endphp
            @isset($options)
                @foreach($options as $key => $val)
                    @php
                        $keyval = explode(':', $val);
                    @endphp
                @if($multiple)
                    <option value="{{trim($keyval[0])}}" @if(in_array(trim($keyval[0]),$res_val))selected @endif>{{isset($keyval[1])? $keyval[1] : ''}}</option>
                @else
                    <option value="{{trim($keyval[0])}}" @if($res_val == trim($keyval[0])) selected @endif>{{isset($keyval[1])? $keyval[1] : ''}}</option>
                @endif
                @endforeach
            @endif
        </select>
    </div>
@else
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        <select  @if($multiple) name="meta[{{$name}}][]" multiple @else name="meta[{{$name}}]" @endif id="{{$id}}">
            @isset($options)
                @foreach($options as $key => $val)
                    @php
                        $keyval = explode(':', $val);
                    @endphp
                    <option value="{{trim($keyval[0])}}">{{isset($keyval[1])? $keyval[1] : ''}}</option>
                @endforeach
            @endif
        </select>
    </div>
@endif
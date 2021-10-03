@php
    $placeholder = isset($placeholder) ? 'data-placeholder="'.$placeholder.'"' : '';
    $multiple = isset($multiple)  && $multiple == true ? 'multiple="multiple"' : '';
@endphp

@isset($resource)
    <div class="form-group {{ isset($class_name) ? $class_name : '' }}">
        <label for="{{$id}}">{{$label}}</label>
        @php
            $options_array = [];
            if(isset($resource->meta[$name])){
                $res_val = $resource->meta[$name];
            } else {
                $res_val = null;
            }
        @endphp
            
            {{-- @isset($options)
                @foreach($options as $key => $val)
                    @php
                        $keyval = explode(':', $val);
                        if(count($keyval) > 1){
                            $options_array[$keyval[0]] = $keyval[1];
                        }else{
                            $options_array[$key] = $val;
                        }
                    @endphp
                @endforeach
            @endif --}}

        {{ Form::select($name, $options, $res_val, ['class' => 'form-control select2', $multiple, $placeholder]) }}
        @isset($info)
            <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
        @endif
    </div>
@else
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
            {{-- @php $options_array = [] @endphp
            @isset($options)
                @foreach($options as $key => $val)
                    @php
                        $keyval = explode(':', $val);
                        if(count($keyval) > 1){
                            $options_array[$keyval[0]] = $keyval[1];
                        }else{
                            $options_array[$key] = $val;
                        }
                    @endphp
                @endforeach
            @endif --}}
        {{ Form::select($name, $options, isset($selected) ? $selected : null, ['class' => 'form-control select2', $multiple, $placeholder]) }}
        @isset($info)
            <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
        @endif
    </div>
@endif
@php
    $placeholder = isset($placeholder) ? 'data-placeholder="'.$placeholder.'"' : '';
    $multiple = isset($multiple)  && $multiple == true ? 'multiple="multiple"' : '';
@endphp
@isset($resource)
    <div class="form-group {{ isset($class_name) ? $class_name : '' }}">
        @php 
            $options_array = [];
            if(isset($resource->meta[$name])){
                $res_val = $resource->meta[$name];
            } else {
                $res_val = null;
            }
        @endphp

        <label for="{{$id}}">{{$label}}</label>
        @php $options_array = [] @endphp
        @isset($options)
            @foreach($options as $key => $val)
                <div class="col-md-12">
                    @if(count($options) > 1)
                        <label> {{ Form::checkbox($name.'[]', $key, $res_val) }} {{ $val }} </label>
                    @else
                        <label> {{ Form::checkbox($name, $key, $res_val) }} {{ $val }} </label>
                    @endif
                </div>
            @endforeach
        @endif
        @isset($info)
            <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
        @endif
    </div>
@else
    <div class="form-group">
        <label for="{{$id}}">{{$label}}</label>
        @php $options_array = [] @endphp
        @isset($options)
            @foreach($options as $key => $val)
                <div class="col-md-12">
                    <label> {{ Form::checkbox($name.'[]', $key, null) }} {{ $val }} </label>
                </div>
            @endforeach
        @endif
        @isset($info)
            <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
        @endif
    </div>
@endif
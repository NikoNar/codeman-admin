{{--@isset($selected)--}}
        <div class="panel panel-default" data-model="{{$relation_name}}">
            <div class="panel-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$relation_name}}" >
                    {{ucwords($relation_name)}}
                </a>
            </div>
            <div id="{{$relation_name}}" class="panel-collapse collapse ">
                <div class="panel-body ">
                    <div class="form-group">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <strong class="relation_name">All</strong>
                                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
                                <ul id="myUL" class="draggables connectedSortable">
                                    @foreach($items as $key => $arr)
                                        @if(isset($attached_relations) && !empty($attached_relations))
                                            @if(!in_array($arr['id'], $attached_relations))
                                                <li class="ui-state-default" data-id="{{$arr['id']}}"><a href="javascript:void(0)">{{$arr['title']}}</a></li>
                                            @endif
                                        @else
                                            <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <span>Attach</span>
                                <ul id="" data-name="{{$relation_name}}" class="dragged connectedSortable">
                                    @foreach($items as $key => $arr)
                                        @if(isset($attached_relations) && !empty($attached_relations))
                                            @if(in_array($arr['id'], $attached_relations))
                                                <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @isset($info)
                <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
            @endif
        </div>

{{--@else--}}
{{--        <div class="panel panel-default" data-model="{{$relation_name}}">--}}
{{--            <div class="panel-heading">--}}
{{--                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$relation_name}}" >--}}
{{--                    {{ucwords($relation_name)}}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div id="{{$relation_name}}" class="panel-collapse collapse ">--}}
{{--                <div class="panel-body ">--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="form-group row">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <strong class="relation_name">All</strong>--}}
{{--                                <ul id="" class="draggables connectedSortable">--}}
{{--                                    @foreach($items as $key => $arr)--}}
{{--                                        <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <span>Attach</span>--}}
{{--                                <ul id="" data-name="{{$relation_name}}" class="dragged connectedSortable">--}}

{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--@endif--}}


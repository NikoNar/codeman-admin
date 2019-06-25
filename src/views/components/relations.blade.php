@isset($selected)
    <div class="form-group">
        <div class="form-group row">
            <div class="col-md-6">
                <strong class="relation_name">{{$relation_name}}</strong>
                <ul id="" class="draggables connectedSortable">
                    @foreach($items as $key => $arr)
                        <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <span>Attach</span>
                <ul id="" data-name="{{$relation_name}}" class="dragged connectedSortable">
                    @foreach($selected as $key => $arr)
                        <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="form-group">
        <div class="form-group row">
            <div class="col-md-6">
                <strong class="relation_name">{{$relation_name}}</strong>
                <ul id="" class="draggables connectedSortable">
                    @foreach($items as $key => $arr)
                        <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <span>Attach</span>
                <ul id="" data-name="{{$relation_name}}" class="dragged connectedSortable">

                </ul>
            </div>
        </div>
    </div>
@endif
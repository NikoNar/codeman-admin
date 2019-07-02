@foreach($data as $key => $val)
    @if($val['type'] != '')
        @switch($val['type'])
            @case('select')
            @include('admin-panel::resource.parts.options.select', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'], 'resource'=>isset($page)?$page : null, 'options'=>explode(',', $val['type_options']), 'multiple'=>array_key_exists('multiple', $val)])
            @break
            @case('textarea')
            @include('admin-panel::resource.parts.options.textarea', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'],'resource'=>isset($page)?$page : null])
            @break
            @case('image')
            @include('admin-panel::resource.parts.options.thumbnail', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'],'resource'=>isset($page)?$page : null])
            @break
            @case('gallery')
            @include('admin-panel::resource.parts.options.gallery', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'],'resource'=>isset($page)?$page : null])
            @break
            @case('editor')
            @include('admin-panel::resource.parts.options.ckeditor', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'type' => $val['type'],'resource'=>isset($page)?$page : null])
            @break
            @default
            @include('admin-panel::resource.parts.options.input', ['id'=>$key, 'label' => $val['label'], 'name' => $val['name'], 'resource'=>isset($page)?$page : null, 'type' => $val['type'], 'options'=>explode(',', $val['type_options'])])
            @break
        @endswitch
    @endif
@endforeach
@isset($attachments)
        @php
            if(isset($page)){
                $full = array_keys($page->meta['attachments'], 'all');
            } else {
                 $full = [];
            }
        @endphp
<hr>
<h4>Attach Resources</h4>
<div class="panel-group" id="accordion">
    @foreach($attachments as $model => $items)
        <div class="panel panel-default attachments" data-model="{{$model}}">
            <div class="panel-heading">
                <h4 class="panel-title">{{ucwords($model)}}
                    <input type="checkbox" name="{{$model}}" value="" @if(in_array($model,$full))checked @endif class="check-all">all
                    <a class="accordion-toggle pull-right" data-toggle="collapse" data-parent="#accordion" href="#{{$model}}" >
                        Custom
                    </a>
                </h4>
            </div>
            <div id="{{$model}}" class="panel-collapse collapse ">
                <div class="panel-body ">
                    <div class="form-group">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <strong class="relation_name">{{$model}}</strong>
                                <ul id="" class="draggables connectedSortable">
                                    @foreach($items as $key => $arr)
                                        @if(isset($selected_attachments) && !empty($selected_attachments))
                                            @if(!in_array($arr['id'], $selected_attachments))
                                                <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                                            @endif
                                        @else
                                            <li class="ui-state-default" data-id="{{$arr['id']}}">{{$arr['title']}}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <span>Attach</span>
                                <ul id="" data-name="{{$model}}" class="dragged connectedSortable">
                                    @foreach($items as $key => $arr)
                                        @if(isset($selected_attachments) && !empty($selected_attachments))
                                            @if(in_array($arr['id'], $selected_attachments))
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
         </div>
    @endforeach
        <input type="hidden" name="meta[attachments]" id="attachments">
</div>
@endif

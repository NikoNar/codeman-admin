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
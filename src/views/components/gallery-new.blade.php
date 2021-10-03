<div class="form-group gallery-container" data-id="{{$id}}">
    <div class="box-header with-border">
        <span class="pull-left">
            {!! Form::label($id, ucwords($label)); !!}
            @isset($info)
                <p><small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small></p>
            @endif
        </span>

        <span class="btn btn-file btn-primary btn-flat media-open multichoose pull-right" data-meta="{{$id}}" >
            <span class="fileupload-new">Select Images</span>
            @if(isset($gallery) && !$gallery->isEmpty())
                @php
                    $input_value = null;
                    foreach ($gallery as $key => $image) {
                        $input_value[$key]['id'] = $image->id;
                        $input_value[$key]['alt'] = $image->alt == $image->pivot->alt ? $image->alt : $image->pivot->alt;
                    }
                    $input_value = json_encode($input_value);
                @endphp
                {!! Form::hidden($name, $input_value, ['class' => 'meta_images']); !!}
            @else
                {!! Form::hidden($name, null , ['class' => 'meta_images']); !!}
            @endif
        </span>
    </div>
    
    <div>
        <div class="clearfix"></div>
        <div class="gallery-show-container col-md-3-gallery sortable-grid" data-meta="{{$id}}" id="sortable-grid">
            <div class="empty-gallery" style="padding-top: 20px; color: #fff; text-transform: uppercase; font-size: 14px; text-align: center; min-height: 300px; background: linear-gradient(-140deg, #36fcef 0%, #1fc8db 51%, #2cb5e8 75%); {{isset($gallery) && !$gallery->isEmpty() ? 'display: none;' : null }}">
                <i class="fa fa-arrow-up" style="font-size: 14px; margin-right: 10px; margin-left: 10px;"></i>
                Select Images for fill the gallery.
            </div>
            @if(!empty($gallery))
                @foreach($gallery as $image)
                    <div class="media-item">
                        <i class="fa fa-times-circle remove"></i>
                        <i class="fa fa-arrows-alt gallery-image-sort"></i>
                        
                        @if(get_file_url($image->filename, 'icon_size'))
                            <img src="{!! get_file_url($image->filename, 'icon_size') !!}" class="thumbnail">
                        @else
                            <img src="{!! asset('admin-panel/images/no-image.jpg') !!}" class="thumbnail">
                        @endif

                        <input name="thumbnail-alt" class="form-control" value="{!! $image->alt == $image->pivot->alt ? $image->alt : $image->pivot->alt !!}" placeholder="Alt Name">
                        <input type="hidden" name="imageable_id" class="form-control" value="{{ $image->id }}">
                    </div>
                @endforeach
            @endif
        </div>
        
        @if(isset($gallery) && !empty($gallery))
            <script type="text/javascript">
                var galleryImagesArr = [];
                @foreach($gallery as $image)
                    galleryImagesArr.push({
                        'id': '{!! $image->id !!}',
                        'alt': '{!! $image->alt == $image->pivot->alt ? $image->alt : $image->pivot->alt !!}'
                    });
                @endforeach
                if (!window.hasOwnProperty('galleryObj')) {
                    window.galleryObj = {};
                }
                    galleryObj[{{$id}}] = galleryImagesArr;
            </script>
        @endif
    </div>
</div>
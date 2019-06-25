<div class="form-group gallery-container" data-id="{{$id}}">
    <div class="box-header with-border">
        {!! Form::label($id, $name); !!}
        <span class="btn btn-file btn-primary btn-flat media-open multichoose pull-right" data-meta="{{$id}}" >
    	        <span class="fileupload-new">Select Images</span>
                @if(isset($resource) && isset($resource->meta[$name]))
                    @php
                    if(is_array($resource->meta[$name])){
                        $value = json_encode($resource->meta[$name]);
                    }
                    @endphp
    	        {!! Form::hidden("meta[$name]", isset($value)? $value: null , ['class' => 'meta_images']); !!}
                    @else
                {!! Form::hidden("meta[$name]", null , ['class' => 'meta_images']); !!}
            @endif
    	    </span>
    </div>
    <div>
        @if(isset($resource->meta[$name]) && !empty($resource->meta[$name]))
            @php
                $gallery = $resource->meta[$name];
            @endphp
        @endif
        <div class="clearfix"></div>
        <div class="gallery-show-container col-md-3-gallery sortable-grid" data-meta="{{$id}}"   id="sortable-grid">
            <div class="empty-gallery" style="padding-top: 20px; color: #fff; text-transform: uppercase; font-size: 14px; text-align: center; min-height: 300px; background: linear-gradient(-140deg, #36fcef 0%, #1fc8db 51%, #2cb5e8 75%); {{isset($gallery) && !empty($gallery) ? 'display: none;' : null }}">
                <i class="fa fa-arrow-up" style="font-size: 14px; margin-right: 10px; margin-left: 10px;"></i>
                Select Images for fill the gallery.
            </div>
            @if(!empty($gallery))
                @foreach($gallery as $image)
                    <div class="media-item">
                        <i class="fa fa-times-circle remove"></i>
                        <i class="fa fa-arrows-alt gallery-image-sort"></i>
                        @if(is_url($image['url']))
                            <img src="{!! $image['url'] !!}" class="thumbnail">
                        @else
                            <img src="{!! url('media/icon_size').'/'.$image['url'] !!}" class="thumbnail">
                        @endif
                        <input name="thumbnail-alt" class="form-control" value="{!! $image['alt'] !!}" placeholder="Alt Name">
                    </div>
                @endforeach
            @endif
        </div>
        @if(isset($gallery) && !empty($gallery))
            <script type="text/javascript">
                var galleryImagesArr = [];
                @foreach($gallery as $image)
                galleryImagesArr.push({'url': '{!! $image['url'] !!}', 'alt': '{!! $image['alt']!!}' });
                @endforeach
                if (!window.hasOwnProperty('galleryObj')) {
                    window.galleryObj = {};
                }
                    galleryObj[{{$id}}] = galleryImagesArr;
                    // console.log(galleryObj);
                // console.log(JSON.stringify(galleryImagesArr));
            </script>
        @endif
    </div>
</div>
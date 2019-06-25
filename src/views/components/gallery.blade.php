<div class="form-group">
    <div class="box-header with-border">
{{--        {{dd(get_defined_vars())}}--}}
        {!! Form::label('meta[gallery]', 'Gallery'); !!}
        <span class="btn btn-file btn-primary btn-flat media-open multichoose pull-right"  >
    	        <span class="fileupload-new">Select Images</span>
    	        {!! Form::hidden('meta[gallery]', null , ['id' => 'images']); !!}
    	    </span>
    </div>
    <div>
        @if(isset($resource->meta['gallery']) && !empty($resource->meta['gallery']) && isJson($resource->meta['gallery']))
            @php
                $gallery = json_decode($resource->meta['gallery']);
            @endphp
        @endif
        <div class="clearfix"></div>
        <div class="gallery-show-container col-md-3-gallery" id="sortable-grid" data-meta="0">
            <div class="empty-gallery" style="padding-top: 20px; color: #fff; text-transform: uppercase; font-size: 14px; text-align: center; min-height: 300px; background: linear-gradient(-140deg, #36fcef 0%, #1fc8db 51%, #2cb5e8 75%); {{isset($gallery) && !empty($gallery) ? 'display: none;' : null }}">
                <i class="fa fa-arrow-up" style="font-size: 14px; margin-right: 10px; margin-left: 10px;"></i>
                Select Images for fill the gallery.
            </div>
            @if(!empty($gallery))
                @foreach($gallery as $image)
                    <div class="media-item">
                        <i class="fa fa-times-circle remove"></i>
                        <i class="fa fa-arrows-alt gallery-image-sort"></i>
                        @if(is_url($image->url))
                            <img src="{!! $image->url !!}" class="thumbnail">
                        @else
                            <img src="{!! url('media/icon_size').'/'.$image->url !!}" class="thumbnail">
                        @endif
                        <input name="thumbnail-alt" class="form-control" value="{!! $image->alt !!}" placeholder="Alt Name">
                    </div>
                @endforeach
            @endif

        </div>

        @if(isset($gallery) && !empty($gallery))
            <script type="text/javascript">
                var galleryImagesArr = [];
                @foreach($gallery as $image)
                galleryImagesArr.push({'url': '{!! $image->url !!}', 'alt': '{!! $image->alt !!}' });
                @endforeach
                console.log(JSON.stringify(galleryImagesArr));
            </script>
        @endif
    </div>
</div>

<div class="gallery">
    <div class="">
        <span class="btn btn-file btn-primary btn-flat col-md-3 media-open multichoose" >
            <span class="fileupload-new">Select Images</span>
            {!! Form::hidden('meta[gallery_images]', null, ['id' => 'images']); !!}
        </span>
        <h4 class="pull-right">Special Guests Gallery Images</h4>
    </div>
    <div class="clearfix"></div>
    <hr>
       <div class="gallery-show-container" id="sortable-grid">
            <div class="empty-gallery" style="padding-top: 20px; color: #fff; text-transform: uppercase; font-size: 26px; min-height: 300px; background: linear-gradient(-140deg, #36fcef 0%, #1fc8db 51%, #2cb5e8 75%);; {{isset($page->meta['gallery_images']) && !empty($page->meta['gallery_images']) && $page->meta['gallery_images'] != "[]" ? 'display: none;' : null }}">
             <i class="fa fa-arrow-up" style="font-size: 26px; margin-right: 10px; margin-left: 15px;"></i>
             Select Images for fill gallery.
            </div>
            @if(isset($page->meta['gallery_images']) && !empty($page->meta['gallery_images']))

                @foreach( json_decode($page->meta['gallery_images']) as $image)
                    <div class="media-item">
                        <i class="fa fa-remove remove"></i>
                        <img src="{!! $image->url !!}" class="thumbnail">
                        <input name="thumbnail-alt" class="form-control" value="{!! $image->alt !!}" placeholder="Alt Name">
                    </div>
                @endforeach
            @endif
    </div>
    @if(isset($page->meta['gallery_images']) && !empty($page->meta['gallery_images']))
        <script type="text/javascript">
            var galleryImagesArr = {!! $page->meta['gallery_images'] !!};
        </script>
    @endif     
</div>
<div class="clearfix"></div>
<br>
<div class="form-group">
	{!! Form::label('description_2', 'Content 2'); !!}
	{!! Form::textarea('meta[description_2]', null, ['class' => 'form-control', 'id' => 'editor-2']); !!}
</div>
    
{{-- About History  JS--}}
<!-- Sortable -->
<script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>
<script type="text/javascript">
    sortEl = document.getElementById('sortable-grid');

    var sortable = Sortable.create(sortEl, {
        // Element dragging ended
        onEnd: function (evt) {
            var itemEl = evt.item;  // dragged HTMLElement
            evt.to;    // target list
            evt.from;  // previous list
            evt.oldIndex;  // element's old index within old parent
            evt.newIndex;  // element's new index within new parent
            old_index = evt.oldIndex - 1;
            new_index = evt.newIndex - 1;

            function arrayMove (array, old_index, new_index){
                if (new_index >= array.length) {
                    var k = new_index - array.length;
                    while ((k--) + 1) {
                        array.push(undefined);
                    }
                }
                array.splice(new_index, 0, array.splice(old_index, 1)[0]);
                
                return array; // for testing purposes
            };

            galleryImagesArr = arrayMove(galleryImagesArr, old_index, new_index);

            $('#images').val(JSON.stringify(galleryImagesArr));

        },
    });
</script>
{{-- End About History --}}

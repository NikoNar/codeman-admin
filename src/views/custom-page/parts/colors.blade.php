<div class="form-group">
    <div class="box">
        <div class="box-header with-border">
            <h4 class="">Colors</h4>
        </div>
        <div class="box-body">
            <table class="table-striped table" id="directors-table">
                <thead>
                <tr>
                    <th class="info">Color Name</th>
                    <th class="info">Color</th>
                    <th class="info" style="width: 20%;">Color Image</th>
                    <th class="info" style="width: 20%;">Product Image</th>
                    <th class="info"></th>
                </tr>
                </thead>
                <tbody>

                @if(isset($colors) &&  null != $colors)
                    @if(isset($product))
                        @foreach($colors as $key => $value)
                            <tr>
                                <td>
                                    <input type="text" name="colors[name][]" required class="form-control no-padding-right" placeholder="Color Name" value="{!! $value[0] !!}" style="width:100%">
                                </td>
                                <td>
                                    <input type="color" name="colors[color][]" class="form-control" value="{!! $value[1] !!}" style="width:100%;">
                                </td>
                                <td>
                                    <div class="fileupload fileupload-new" data-provides="fileupload border-right">
                                        <div class="fileupload-preview thumbnail color-image-upload">
                                            @if(isset($value[2]) && $value[2] != '')
                                                <img src="{!! $value[2] !!}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                            @else
                                                <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                            @endif
                                        </div>
                                        <div>
                                                <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img">
                                                    <span class="fileupload-new"><i class="fa fa-camera"></i></span>
                                                </span>
                                            <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div class="form-group  col-md-12 no-padding">
                                                <label for="thumbnail">Image Url</label>
                                                <input type="text" name="colors[pic][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media" value="{!! isset($value[2]) ? $value[2] : '' !!}" style="width:100%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fileupload fileupload-new" data-provides="fileupload border-right">
                                        <div class="fileupload-preview thumbnail" style="width: 100%;">
                                            @if(isset($value[3]) && $value[3] != '')
                                                <img src="{!! $value[3] !!}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                            @else
                                                <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                            @endif
                                        </div>
                                        <div>
                                                <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img">
                                                    <span class="fileupload-new"><i class="fa fa-camera"></i></span>
                                                </span>
                                            <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div class="form-group  col-md-12 no-padding">
                                                <label for="thumbnail">Image Url</label>
                                                <input type="text" name="colors[prod][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media" value="{!! isset($value[3]) ? $value[3] : '' !!}" style="width:100%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-md btn-flat btn-danger remove-color-row color-count" type="button"  data-count="{{count($colors[0])}}"><i class="fa fa-minus"> </i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td>
                            <input type="text" name="colors[name][]" required class="form-control no-padding-right" placeholder="Color Name" style="width:100%">
                        </td>
                        <td>
                            <input type="color" name="colors[color][]" class="form-control"  style="width:100%;">
                        </td>
                        <td>
                            <div class="fileupload fileupload-new" data-provides="fileupload border-right">
                                <div class="fileupload-preview thumbnail color-image-upload">
                                    <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                </div>
                                <div>
                                        <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img">
                                            <span class="fileupload-new"><i class="fa fa-camera"></i></span>
                                        </span>
                                    <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="form-group  col-md-12 no-padding">
                                        <label for="thumbnail">Image Url</label>
                                        <input type="text" name="colors[pic][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media"  style="width:100%">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fileupload fileupload-new" data-provides="fileupload border-right">
                                <div class="fileupload-preview thumbnail" style="width: 100%;">
                                    <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                </div>
                                <div>
                                        <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img">
                                            <span class="fileupload-new"><i class="fa fa-camera"></i></span>
                                        </span>
                                    <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <div class="clearfix"></div>
                                    <br>
                                    <div class="form-group  col-md-12 no-padding">
                                        <label for="thumbnail">Image Url</label>
                                        <input type="text" name="colors[prod][]" class="form-control no-padding-right" placeholder="Enter image url or chosse from media"  style="width:100%">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-md btn-flat btn-danger remove-color-row" type="button"><i class="fa fa-minus"> </i></button>
                        </td>
                    </tr>
                @endif

                </tbody>
                <tfoot>
                <tr>
                    <th colspan="4">
                        <button class="btn btn-flat btn-md btn-primary" type="button" id="add-color-row"><i class="fa fa-plus"></i> Add Color </button>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{!! Form::open(['action' => "Admin\HomeController@update", 'method' => 'POST']) !!}
    <div class="box">
        <div class="box-body">
            <h3>Home Slider</h3>
            <table class="table-striped table sortable-table" id="slider_table">
                <thead>
                    <tr>
                        <th class="info" style="width: 30%">Slider Image</th>
                        <th class="info">From To Date</th>
                        <th class="info" style="width: 40%">Main Text   </th>
                        <th class="info"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($homeSliders) && !$homeSliders->isEmpty())
                        @foreach($homeSliders as $slide)
                            <tr data-id="{!! $slide->id !!}">
                                <td>
                                        <div class="fileupload fileupload-new" data-provides="fileupload border-right">
                                                <div class="fileupload-preview thumbnail" style="width: 100%;">
                                                        @if($slide->thumbnail)
                                                                <img src="{!! $slide->thumbnail !!}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                                        @else
                                                                <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">

                                                        @endif
                                                </div>
                                                <div>
                                                        <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img">
                                                                <span class="fileupload-new"><i class="fa fa-camera"></i></span>
                                                        </span>
                                                        <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload">
                                                                <i class="fa fa-trash"></i>
                                                        </a>
                                                </div>
                                                <div class="form-group  col-md-12 no-padding">
                                                        <label for="thumbnail">Image Url</label>
                                                        <input type="text" name="slider[thumbnail][]" class="form-control no-padding-right thumbnail" placeholder="Enter image url or chosse from media" value="{!! $slide->thumbnail !!}" style="width:100%">
                                                </div>
                                        </div>
                                </td>
                                <td>
                                        <label for="">English</label>
                                        <input type="text" name="slider[from_to_date_en][]"  class="form-control no-padding-right" placeholder="From To Date" style="width:100%" value="{!! $slide->from_to_date_en !!}">
                                        <label for="">Armenian</label>
                                        <input type="text" name="slider[from_to_date_arm][]"  class="form-control no-padding-right" placeholder="From To Date" style="width:100%" value="{!! $slide->from_to_date_arm !!}">
                                        <hr>

                                </td>
                                <td>
                                        <label for="">English</label>
                                        <textarea name="slider[main_text_en][]" class="form-control ckeditor" placeholder="Text" style="width:100%; height:150px">{!! $slide->main_text_en !!}</textarea>
                                        <label for="">Armenian</label>
                                        <textarea name="slider[main_text_arm][]" class="form-control ckeditor" placeholder="Text" style="width:100%; height:150px">{!! $slide->main_text_arm !!}</textarea>
                                        
                                </td>
                                <td>
                                        <input type="hidden" name="slider[id][]" value="{!! $slide->id !!}">
                                        <button class="btn btn-md btn-flat btn-danger remove-slider-row" type="button"><i class="fa fa-minus"> </i></button>
                                </td>
                            </tr>
                        @endforeach 
                    @else
                        <tr>
                                <td>
                                        <div class="fileupload fileupload-new" data-provides="fileupload border-right">
                                                <div class="fileupload-preview thumbnail" style="width: 100%;">
                                                        <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                                                </div>
                                                <div>
                                                        <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img">
                                                                <span class="fileupload-new"><i class="fa fa-camera"></i></span>
                                                        </span>
                                                        <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload">
                                                                <i class="fa fa-trash"></i>
                                                        </a>
                                                </div>
                                                <div class="form-group  col-md-12 no-padding">
                                                        <label for="thumbnail">Image Url</label>
                                                        <input type="text" name="slider[thumbnail][]" class="form-control no-padding-right thumbnail" placeholder="Enter image url or chosse from media"  style="width:100%">
                                                </div>
                                        </div>
                                </td>
                                <td>
                                        <label for="">English</label>
                                        <input type="text" name="slider[from_to_date_en][]"  class="form-control no-padding-right" placeholder="From To Date" style="width:100%">
                                        <label for="">Armenian</label>
                                        <input type="text" name="slider[from_to_date_arm][]"  class="form-control no-padding-right" placeholder="From To Date" style="width:100%">
                                        
                                </td>
                                <td>
                                        <label for="">English</label>
                                        <textarea name="slider[main_text_en][]" class="form-control ckeditor" placeholder="Text" style="width:100%; height:150px"></textarea>
                                        <label for="">Armenian</label>
                                        <textarea name="slider[main_text_arm][]" class="form-control ckeditor" placeholder="Text" style="width:100%; height:150px"></textarea>
                                </td>
                                <td>
                                        <button class="btn btn-md btn-flat btn-danger remove-slider-row" type="button"><i class="fa fa-minus"> </i></button>
                                </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">
                            <button class="btn btn-flat btn-md btn-primary" type="button" id="add-slider-row"><i class="fa fa-plus"></i> Add Slide </button>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" name="modelName" id="modelName" value="{!! isset($homeSliders) && !$homeSliders->isEmpty() ? class_basename($homeSliders[0]) : null !!}" >
            <div class="clearfix"></div>
            <hr>
            <div class="col-md-12">
                    {!! Form::submit('Save Changes', ['class' => 'btn btn-md btn-success btn-flat pull-left']) !!}
            </div>
        </div>
    </div>
{!! Form::close() !!}
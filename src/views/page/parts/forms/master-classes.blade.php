@section('after-style')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">
@endsection
<table class="table-striped table" id="films-table">
    <thead>
        <tr>
            <th class="info" style="width: 20%">Person Name</th>
            <th class="info" style="width: 20%">Person Bio</th>
            <th class="info" style="width: 30%">Person Image</th>
            <th class="info" style="width: 10%;"></th>
        </tr>
    </thead>
    <tbody>
        @if(isset($page->meta) &&  isset($page->meta['master_classes']) && null != $master_classes = $page->meta['master_classes'])
            @php
                $master_classes = json_decode($master_classes, true);
                $counter = 0;
            @endphp
            @if(is_array($master_classes) && !empty($master_classes))
                @foreach($master_classes as $key => $master_class)
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="">Name</label><br>
                                <input class="form-control" name="meta[master_classes][{!! $counter !!}][name]" value="{!! isset($master_class['name']) ? $master_class['name'] : null !!}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="">Date</label><br>
                                <input class="form-control datepicker" name="meta[master_classes][{!! $counter !!}][date]" value="{!! isset($master_class['date']) ? $master_class['date'] : null !!}" type="text" style="width: 100%">
                            </div>
                            <div class="form-group">
                                <label for="">At</label><br>
                                <input class="form-control" name="meta[master_classes][{!! $counter !!}][At]" value="{!! isset($master_class['At']) ? $master_class['At'] : null !!}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="">Venue</label><br>
                                <input class="form-control" name="meta[master_classes][{!! $counter !!}][venue]" value="{!! isset($master_class['venue']) ? $master_class['venue'] : null !!}" type="text">
                            </div>
                        </td>
                           
                        <td>
                            <textarea name="meta[master_classes][{!! $counter !!}][bio]" class="form-control" rows="12" style="height: 100%;">{!! isset($master_class['bio']) ? $master_class['bio'] : null !!}</textarea>
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-preview thumbnail" style="width: 100%;">
                                        <img src="{{ isset($master_class['thumbnail']) ? $master_class['thumbnail'] : asset('admin-panel/images/no-image.jpg')}}" class="img-responsive thumbnail-image" alt="No Featured Image" onerror="imgError(this);">
                                    </div>
                                    <div>
                                        <span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
                                            <span class="fileupload-new">Select image</span>
                                            {!! Form::hidden('meta[master_classes]['.$counter.'][thumbnail]', isset($master_class['thumbnail']) ? $master_class['thumbnail'] : null, ['class' => 'thumbnail']); !!}
                                        </span>
                                        <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload" >Remove</a>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>  
                        </td>
                        <td>
                            <button class="btn btn-md btn-flat btn-danger remove-film-row" type="button">
                                <i class="fa fa-minus"> </i>
                            </button>
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach
            @endif
        @endif
        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">
                <button class="btn btn-flat btn-md btn-primary" type="button" id="add-film-row"><i class="fa fa-plus"></i> Add Master Class </button>
            </th>
        </tr>
    </tfoot>
</table>
@section('after-script')
    <!-- date-range-picker -->
    <script src="{{ asset('admin/bower_components/moment/min/moment.min.js') }} "></script>
    <script src="{{ asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }} "></script>

    <!-- bootstrap datepicker -->
    <script src="{{ asset('admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }} ">
    </script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }} "></script>

    <!-- DataTables -->
    <script src="{{ asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $('input.datepicker').datepicker({});
        
        var films_table = $('#films-table').DataTable({
          'paging'      : false,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          // 'autoWidth'   : true
        });
        @if(isset($master_classes) && is_array($master_classes))
            var filmsCounter = {{ count($master_classes) }};
        @else
            var filmsCounter = 0;
        @endif
        $('body').off('click', '#add-film-row').on('click', '#add-film-row', function (e) {
            e.preventDefault();
            films_table.row.add( [
                '<td> <div class="form-group"> <label for="">Name</label><br> <input class="form-control" name="meta[master_classes]['+filmsCounter+'][name]" type="text"> </div> <div class="form-group"> <label for="">Date</label><br> <input class="form-control datepicker" name="meta[master_classes]['+filmsCounter+'][date]" type="text" style="width: 100%"> </div> <div class="form-group"> <label for="">At</label><br> <input class="form-control" name="meta[master_classes]['+filmsCounter+'][At]" type="text"> </div> <div class="form-group"> <label for="">Venue</label><br> <input class="form-control" name="meta[master_classes]['+filmsCounter+'][venue]" type="text"> </div> </td>', '<td> <textarea name="meta[master_classes]['+filmsCounter+'][bio]" class="form-control" rows="12" style="height: 100%;"></textarea> </td>', '<td> <div class="form-group"> <div class="fileupload fileupload-new" data-provides="fileupload"> <div class="fileupload-preview thumbnail" style="width: 100%;"> <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive thumbnail-image" alt="No Featured Image" onerror="imgError(this);"> </div> <div> <span class="btn btn-file btn-primary btn-flat col-md-6 media-open"> <span class="fileupload-new">Select image</span> <input type="hidden" name="meta[master_classes]['+filmsCounter+'][thumbnail]" class="thumbnail"> </span> <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload" >Remove</a> <div class="clearfix"></div> </div> </div> </div> </td>', '<td> <button class="btn btn-md btn-flat btn-danger remove-film-row" type="button"> <i class="fa fa-minus"> </i> </button> </td>',
            ]  ).draw();
            $('input[name="meta[master_classes]['+filmsCounter+'][date]"]').datepicker({}).datepicker('setDate', new Date());
            filmsCounter++;
        } );
        $('body').off('click', '.remove-film-row').on('click', '.remove-film-row', function (e) {
            e.preventDefault();
            films_table.row( $(this).closest('tr') ).remove().draw();
            if(filmsCounter > 0){
                filmsCounter--;
            }
        });

        $('body').off('chnage', 'input.yearpicker').on('change', 'input.yearpicker', function(){
            var container = $(this).closest('tr');
            console.log(container);
            var full_date = this.value;
            full_date = full_date.split("/");
            var year = full_date[full_date.length - 1];
            console.log(year);
            $.ajax({
                type: 'POST',
                url: app.ajax_url+ '/admin/resource/get-current-year-film-names',
                dataType: 'JSON',
                data: {'_token' : $('meta[name="csrf-token"]').attr('content'), 'year' : year},
                success: function(data){
                    console.log(data);
                    var select = container.find('select.films_dropdown');
                    select.html('');
                    if(data.success == true){
                        if(data.html != null || data.html != 'null'){
                            if(typeof data.html == 'object'){
                                select.html('<option value="">Select Film</option>');
                                $.each(data.html, function(k, v){
                                    select.append('<option value="'+k+'">'+v+'</option>');
                                });
                                select.select2();
                            }
                        }
                    }
                }
            });
        });

        $('body').off('change', 'select.films_dropdown').on('change', 'select.films_dropdown', function(){
            var id = this.value;
            console.log(this.options[this.selectedIndex].value);
            var container = $(this).closest('tr');
            var imageContainer = container.find('.thumbnail');

            $.ajax({
                type: 'POST',
                url: app.ajax_url+ '/admin/resource/get-film-some-data',
                dataType: 'JSON',
                data: {'_token' : $('meta[name="csrf-token"]').attr('content'), 'id' : id},
                success: function(data){
                    console.log(data);
                    if(data.success == true){
                        if(data.html.thumbnail != null || data.html.thumbnail != 'null'){
                            imageContainer.find('img').attr('src', data.html.thumbnail);
                            imageContainer.find('input.film-thumbnail').val(data.html.thumbnail);
                            imageContainer.find('input.film-directors').val(data.directors);
                            imageContainer.find('input.film-slug').val(data.html.slug);
                            imageContainer.find('input.film-title').val(data.html.title);
                        }
                    }
                }
            });
        });
    </script>
@endsection()
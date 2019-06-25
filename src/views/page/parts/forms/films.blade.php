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
            <th class="info" style="width: 20%">Film Year</th>
            <th class="info" style="width: 30%">Film Name</th>
            <th class="info" style="width: 20%">Image</th>
            <th class="info" style="width: 10%;"></th>
        </tr>
    </thead>
    <tbody>
        @if(isset($page->meta) &&  isset($page->meta['films']) && null != $films = $page->meta['films'])
            @php
                $films = json_decode($films, true);
                // dd($films);
                $counter = 0;
            @endphp
            @if(is_array($films) && !empty($films))
                @foreach($films as $key => $film)
                
                    @php
                        $filmsArray = null;
                        if($film['year'] != null && $film['year'] != ''){
                            $filmsArray = getFilmsByYear($film['year']);
                        }
                    @endphp
                    <tr>
                        <td>
                            <input class="form-control yearpicker" name="meta[films][{!! $counter !!}][year]" type="text" value="{{  $film['year'] }}">
                        </td>
                        <td>
                            <select class="form-control films_dropdown" style="width:100%" name="meta[films][{!! $counter !!}][id]">
                                <option value="">Select a Film</option>
                                @if(isset($filmsArray) && is_array($filmsArray) && !empty($filmsArray))
                                    @foreach($filmsArray as $f_id => $f_name)
                                        <option value="{!! $f_id !!}" @if($f_id == $film['id']) selected="selected" @endif>{!! $f_name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td>
                            <div class="fileupload fileupload-new" data-provides="fileupload border-right" style="width: 100%">
                                <div class="fileupload-preview thumbnail" style="width: 100%;">
                                    <img src="{{  $film['thumbnail'] }}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);">
                                    <input type="hidden" name="meta[films][{!! $counter !!}][thumbnail]" class="film-thumbnail" value="{{   $film['thumbnail'] }}">
                                    <input type="hidden" name="meta[films][{!! $counter !!}][slug]" class="film-slug" value="{{   $film['slug'] }}">
                                    <input type="hidden" name="meta[films][{!! $counter !!}][directors]" class="film-directors" value="{{   $film['directors'] }}">
                                    <input type="hidden" name="meta[films][{!! $counter !!}][title]" class="film-title" value="{{   $film['title'] }}">

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
        @else
            
        @endif
        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">
                <button class="btn btn-flat btn-md btn-primary" type="button" id="add-film-row"><i class="fa fa-plus"></i> Add Film </button>
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
        $('input.yearpicker').datepicker({
            format: "yyyy", // Notice the Extra space at the beginning
            viewMode: "years", 
            minViewMode: "years",
            changeMonth: false,
            changeYear: true,
            changeDay: false,
        });

        var films_table = $('#films-table').DataTable({
          'paging'      : false,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          // 'autoWidth'   : true
        });
        @if(isset($films) && is_array($films))
        var filmsCounter = {{ count($films) }};
        @else
        var filmsCounter = 0;
        @endif
        $('body').off('click', '#add-film-row').on('click', '#add-film-row', function (e) {
            e.preventDefault();
            films_table.row.add( [
                '<td> <input class="form-control yearpicker" name="meta[films]['+filmsCounter+'][year]" type="text"></td>',
                '<td><select class="form-control films_dropdown" style="width:100%" name="meta[films]['+filmsCounter+'][id]"><option selected="selected" value="">Select a Film</option></select></td>',
                '<td> <div class="fileupload fileupload-new" data-provides="fileupload border-right" style="width: 100%"> <div class="fileupload-preview thumbnail" style="width: 100%;"> <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);"><input type="hidden" name="meta[films]['+filmsCounter+'][thumbnail]" class="film-thumbnail"><input type="hidden" name="meta[films]['+filmsCounter+'][slug]" class="film-slug"><input type="hidden" name="meta[films]['+filmsCounter+'][directors]" class="film-directors"><input type="hidden" name="meta[films]['+filmsCounter+'][title]" class="film-title"></div> </div></td>',
                '<td> <button class="btn btn-md btn-flat btn-danger remove-film-row" type="button"><i class="fa fa-minus"> </i></button> </td>',
            ]  ).draw();
            $('input[name="meta[films]['+filmsCounter+'][year]"]').datepicker({
                format: "yyyy", // Notice the Extra space at the beginning
                viewMode: "years", 
                minViewMode: "years",
                changeMonth: false,
                changeYear: true,
                changeDay: false,
            }).datepicker('setDate', new Date());
            $('select').select2();
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
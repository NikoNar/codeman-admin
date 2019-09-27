@extends('admin-panel::layouts.app')
@section('style')
    <!-- DataTables -->
    {{--	<link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">--}}
@endsection
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <h3>Settings</h3>
        <div class="box">
            <div class="box-body">
                <div class="clearfix"></div>
                <br>

                <div id="resource-container">
                    {!! Form::model($settings, ['route' => 'setting.update', 'method' => 'POST', 'files' => true]) !!}
                    <div class="col-md-12">
                        <div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
                            {!! Form::label('site_name', 'Site Name:') !!}
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-font"></span>
								    </span>
                                    {!! Form::text('site_name', null, ['class' => 'form-control', 'placeholder' => env('APP_NAME')]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
                            {!! Form::label('site_email', 'Site Email:') !!}
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-envelope"></span>
								    </span>
                                    {!! Form::text('site_email', null, ['class' => 'form-control', 'placeholder' => env('APP_EMAIL')]) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
                            {{ Form::label('index', 'Home Page:') }}
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="input-group">
								    <span class="input-group-addon">
								        <span class="fa fa-home"></span>
								    </span>
                                    {{ Form::select('index', $pages, isset($selected) && $selected != null ? $selected : null, ['placeholder' => 'Please Select']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
                            {{ Form::label('all_langs', 'Supported Languages:') }}
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="input-group">
								    <span class="input-group-addon">
								        <span class="fa fa-globe"></span>
								    </span>
                                    @include('admin-panel::layouts.parts._all_languages')
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
                            {{ Form::label('default_lang', 'Default Language:') }}
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="input-group">
								    <span class="input-group-addon">
								        <span class="fa fa-language"></span>
								    </span>
                                    {{ Form::select('default_lang', $languages, isset($selected) && $selected != null ? $selected : null) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container" >
                    <div id="additional_settings">
                        @if(isset($additional_settings))
                            @foreach($additional_settings as $key => $value)
                                <div class="card setting mb-3" data-i="{{$key}}">
                                    <div class="card-body row col-md-12">
                                        <div class="col-md-6 form-group">
                                            <label for="">Type</label>
                                            <input type="text" name="" id="" class="form-control ">
                                        </div>
                                        <div class="col-md-1">
                                            <label for="">Key</label>
                                            <input type="text" name="" value="" id="" class="form-control" >
                                        </div>
                                        <div class="col-md-1">
                                            <label for="">Value</label>
                                            <input type="text" name="" value="" id="" class="form-control" >
                                        </div>

                                        <div class="col-md-1">
                                            <label for="">Remove</label>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card setting mb-3" data-i="0">
                                <div class="card-body row col-md-12">
                                    <div class="col-md-6">
                                        <label for="">Type</label>
                                        <select  name="data[0][type]" id="" class="form-control">
                                            <option value="text">text</option>
                                            <option value="image">image</option>
                                            <option value="d">d</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="">Key</label>
                                        <input type="text" name="data[0][key]" value="" id="" class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="">Value</label>
                                        <input type="text" name="data[0][value]" value="" id="" class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="">Remove</label>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                            <div class="mb-5 float-right">
                                <p>
                                    <small class="pl-3 text-right">
                                        <a href="javascript:void(0)" class="link-active btn-primary  btn btn-xs" id="add-choice"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
                                    </small>
                                </p>
                            </div>
                    </div>
                </div>


                <a href="{{route('sitemap.generate')}}" class="btn btn-success pull-right">Generate Site
                    Map</a>
                {!! Form::submit('Save Changes', ['class'=> 'btn btn-success btn-flat col-md-12']) !!}
                {!! Form::close() !!}
            </div>
        </div>
        <!-- /.box-footer-->
    </div>
    <div class="clearfix"></div>
@endsection

@section('script')
    <script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#add-choice').on('click', function (e) {
                var max = 0;
                var i = 0;
                $('body').find(".setting[data-i]").each(function () {
                    var value = $(this).attr("data-i");
                    i = max < value ? value : max;
                    i = parseInt(i) + 1;
                });
                let html = '\
                <div class="card  setting mb-3" data-i="'+i+'">\
                    <div class="card-body row col-md-12">\
                        <div class="col-md-6 form-group">\
                            <label for="">Type</label>\
                            <select   name="data['+i+'][type]"  id="" class="form-control" >\
                                <option value="text">text</option>\
                                <option value="image">image</option>\
                                <option value="d">d</option> \
                                </select>\
                        </div>\
                        <div class="col-md-1 form-group">\
                            <label for="">Key</label>\
                            <input type="text"   name="data['+i+'][key]" value="new-'+i+'"    id="" class="form-control">\
                        </div>\
                        <div class="col-md-1 form-group">\
                            <label for="">Value</label>\
                            <input type="text"   name="data['+i+'][val]" value="new-'+i+'"    id="" class="form-control">\
                        </div>\
                        <div class="col-md-1">\
                            <label for="">Remove</label>\
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></a>\
                        </div>\
                    </div>\
                </div>';

                $(html).appendTo($('#additional_settings'));

            });
            $('body').off('click', '.remove-row').on('click','.remove-row', function(e){
                $(this).closest('.setting').remove();
            });

        })
    </script>


@endsection

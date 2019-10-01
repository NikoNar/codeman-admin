@extends('admin-panel::layouts.app')
@section('style')
    <!-- DataTables -->
    {{--	<link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">--}}
@endsection
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <a href="{{route('sitemap.generate')}}" class="btn btn-success pull-right" style="margin-top: 15px">Generate Site
            Map</a>
        <h3>Settings</h3>
        <div class="box">
            <div class="box-body">
                <div class="clearfix"></div>
                <br>
                {!! Form::model($settings, ['route' => 'setting.update', 'method' => 'POST', 'files' => true]) !!}
                <div id="resource-container">
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

                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <h4>Social Icons</h4>
                        <hr>
                        <div class="social-icons-group">
                            @if(isset($settings) && isset($settings['social']))
                                @foreach($settings['social'] as $key => $value)
                                    <div class="item">
                                        <div class="col-md-4">
                                            @include('admin-panel::components.iconpicker', ['name' => "social[$key][name]" , 'value'=>$value->name,  'social'=>true])
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <div class='input-group'>
												    <span class="input-group-addon">
												        <span class="fa fa-link"></span>
												    </span>
                                                    {!! Form::text('social['.$key.'][url]', $value->url ?? null,  ['class' => 'form-control social_icon_url', 'placeholder' => 'Socioal Site Url', 'required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <span class="fa fa-minus btn btn-danger btn-flat remove-soc"></span>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                        </div>
                        <a class="btn btn-success btn-flat pull-right add-social-row"> Add New Social Icon</a>

                        <div class="clearfix"></div>

                    </div>



{{--                </div>--}}
{{--                <div class="resource-container">--}}
                    <div id="additional_settings">
                        <h4>Additional Settings</h4>
                        <hr>
                        @if(isset($additional_settings))
                            @foreach($additional_settings as $key => $value)
                                <div class="card setting mb-3" data-i="{{$key}}">
                                    <div class="card-body row col-md-12">
                                        <div class="col-md-2 form-group">
                                            <select   name="data[{{$key}}][type]"  id="" class="form-control setting-type" data-id="{{$key}}">
                                                <option value="text" @if($value['type'] == 'text') {{'selected'}} @endif>Text</option>
                                                <option value="ckeditor" @if($value['type'] == 'ckeditor') {{'selected'}} @endif>Editor</option>
                                                <option value="thumbnail" @if($value['type'] == 'thumbnail') {{'selected'}} @endif>Image</option>
                                                <option value="iconpicker" @if($value['type'] == 'iconpicker') {{'selected'}} @endif>Icon</option>
                                                <option value="datetimepicker" @if($value['type'] == 'datetimepicker') {{'selected'}} @endif>Date</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <input type="text"   name="data[{{$key}}][key]"  placeholder="Key"  value="{{$value['key']}}" id="" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group setting-value">
                                            @switch($value['type'])
                                                @case('text')
                                                @include('admin-panel::components.text', ['name' => "data[$key][val]", 'value'=>$value['value']])
                                                @break
                                                @case('ckeditor')
                                                @include('admin-panel::components.ckeditor', ['name' => "data[$key][val]", 'value'=>$value['value']])
                                                @break
                                                @case('thumbnail')
                                                @include('admin-panel::components.thumbnail', ['name' => "data[$key][val]", 'value'=>$value['value']])
                                                @break
                                                @case('iconpicker')
                                                @include('admin-panel::components.iconpicker', ['name' => "data[$key][val]", 'value'=>$value['value']])
                                                @break
                                                @case('datetimepicker')
                                                @include('admin-panel::components.datetimepicker', ['name' => "data[$key][val]", 'value'=>$value['value']])
                                                @break
                                            @endswitch
                                        </div>
                                        <div class="col-md-1">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="mb-5 pull-right">
                        <a href="javascript:void(0)" class="btn btn-success pull-righ" id="add-setting" style="margin-bottom: 15px;"><i class="fa fa-plus" aria-hidden="true" ></i> Add </a>
                    </div>

                    {!! Form::submit('Save Changes', ['class'=> 'btn btn-success btn-flat col-md-12']) !!}
                    {!! Form::close() !!}
                </div>
                <div id="item-example" style="display: none;">
                    <div class="item">
                        <div class="col-md-4">
                            @include('admin-panel::components.iconpicker', ['name' => "social[0][name]", 'value'=>null, 'social'=>true])
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class='input-group'>
		    					    <span class="input-group-addon">
		    					        <span class="fa fa-link"></span>
		    					    </span>
                                    {!! Form::text('social[0][url]', null, ['class' => 'form-control social_icon_url', 'placeholder' => 'Socioal Site Url', 'requried']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <span class="fa fa-minus btn btn-danger btn-flat remove-soc"></span>
                            </div>
                        </div>
                    </div>
                </div>
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
            $('#add-setting').on('click', function (e) {
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
                        <div class="col-md-2 form-group">\
                            <select   name="data['+i+'][type]"  id="" class="form-control setting-type" data-id="'+i+'">\
                                <option value="text">Text</option>\
                                <option value="ckeditor">Editor</option>\
                                <option value="thumbnail">Image</option>\
                                <option value="iconpicker">Icon</option>\
                                <option value="datetimepicker">Date</option>\
                            </select>\
                        </div>\
                        <div class="col-md-3 form-group">\
                            <input type="text"   name="data['+i+'][key]"  placeholder="Key"   id="" class="form-control">\
                        </div>\
                        <div class="col-md-6 form-group setting-value">\
                            <input type="text"   name="data['+i+'][val]"     id="" class="form-control">\
                        </div>\
                        <div class="col-md-1">\
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></a>\
                        </div>\
                    </div>\
                </div>';

                $(html).appendTo($('#additional_settings'));

            });
            $('body').off('click', '.remove-row').on('click','.remove-row', function(e){
                $(this).closest('.setting').remove();
            });


            $('body').off('change', '.setting-type').on('change', '.setting-type', function(){
                let type = $(this).val();
                let current  = $(this).closest('.card-body').find('.setting-value');
                let index = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url:'settings/'+type+'/'+index,
                    dataType: 'JSON',
                    success: function(data){
                        current.html(data.html);
                        $('.datetimepicker-simple').datetimepicker();
                        $('.iconpicker').iconpicker();
                        $(function () {
                            if($('#editor-'+index+'').length > 0){
                                // CKEDITOR.replaceClass('.ckeditor');
                                CKEDITOR.replace('editor-'+index)
                            }
                        })

                    }
                });
            })


            // $("body").off('change', '.social_icon_name').on('change', '.social_icon_name', function(){
            //     var icon = $(this).val();
            //     console.log(icon)
            //     $(this).siblings('.input-group-addon').find('span').remove();
            //     $(this).siblings('.input-group-addon').append('<span class="fa'+icon+'"></span>').html('<i class="fa '+icon+'"></i>');
            // });
            $("body").off('click', '.remove-soc').on('click', '.remove-soc', function(){
                $(this).closest('.item').remove();

            });
            $("body").off('click', '.add-social-row').on('click', '.add-social-row', function(){
                var container  = $('.social-icons-group');
                var item = $('#item-example').find('.item').find('.select2-container').remove();
                var item = $('#item-example').find('.item').clone();
                item.find('.social-iconpicker').attr('name', "social["+$('.social-iconpicker').length+"][name]");
                item.find('.social_icon_url').attr('name', "social["+$('.social_icon_url').length+"][url]");
                container.append(item);
                $('.iconpicker').iconpicker();
                $('select').select2();
            });


        })
    </script>


@endsection

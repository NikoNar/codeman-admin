{{-- {{ dd($product_vendors) }} --}}
@extends('admin-panel::layouts.app')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('admin-panel/plugins/iCheck/all.css') }}">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin-panel/plugins/timepicker/bootstrap-timepicker.min.css') }}">

@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Edit product</h3>

            <a href="{{ route("product.create") }}" class="btn btn-primary btn-flat pull-right ">Add New product</a>
            {{-- @if(isset($parent_lang_id) || isset($product) && $product->lang == 'arm')
                @if(isset($parent_lang_id))
                    <a href="{{ action("Admin\NewsController@edit, [$parent_lang_id]") }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
                @else
                    <a href="{{ action("Admin\NewsController@edit", [$product->parent_lang_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>
                @endif
            @else
                <a href="{{ action("Admin\NewsController@translate", [$product->id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to Armenian</a>
            @endif --}}
        </div>
        <div class="box-body">
            @include('admin-panel::product.parts.forms._create_edit_form')
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@section('script')
    <!-- Select2 -->
    <script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

    <script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('product/jsvalidation/js/jsvalidation.js')}}"></script>

    <!-- date-range-picker -->
    <script src="{{ asset('admin-panel/bower_components/moment/min/moment.min.js') }} "></script>

    <script src="{{ asset('admin-panel/bower_components/bootstrap-daterangepicker/daterangepicker.js') }} "></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('admin-panel/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }} "></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('admin-panel/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('admin-panel/plugins/timepicker/bootstrap-timepicker.min.js') }} "></script>

    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('admin-panel/plugins/iCheck/icheck.min.js') }}"></script>

    {!! JsValidator::formRequest('\App\Http\Requests\ProductRequest') !!}
    <script>
        CKEDITOR.replace('editor');
        CKEDITOR.replace('short_description_editor');

        $(function () {
            $('select').select2();
        })
        $('#datepicker').datepicker({})
        //Timepicker
        $('#timepicker').timepicker({
            showInputs: false
        })
    </script>
    <script>
        $(document).ready(function(){
            var def_lang = $('.languages-product').val();
            $('body').off('change', '.languages-product').on('change', '.languages-product', function(e){
                var id = $('input[name="product_id"]').val();
                if(id){
                    if(!confirm("translate? All not saved data will be lost!")){
                        e.preventDefault();
                        $(this).val(def_lang);
                        $(this).select2('destroy');
                        $(this).select2();
                    } else {
                        var lang = $('.languages-product').val();
                        window.location.href='/admin/product/translate/'+id+'/'+lang;
                    }
                }
            });

            $('.gallery-show-container').each(function(){
                var gallery_id = $(this).data('meta');
                if (window.hasOwnProperty('galleryObj')){
                    var currentGallery = galleryObj[gallery_id];
                }
                var sortable = Sortable.create(this, {
                    // Element dragging ended
                    onEnd: function (evt) {
                        var itemEl = evt.item;  // dragged HTMLElement
                        evt.to;    // target list
                        evt.from;  // previous list
                        evt.oldIndex;  // element’s old index within old parent
                        evt.newIndex;  // element’s new index within new parent
                        old_index = evt.oldIndex - 1;
                        new_index = evt.newIndex - 1;

                        function arrayMove(array, old_index, new_index) {
                            if (new_index >= array.length) {
                                var k = new_index - array.length;
                                while ((k--) + 1) {
                                    array.push(undefined);
                                }
                            }
                            array.splice(new_index, 0, array.splice(old_index, 1)[0]);
                            return array; // for testing purposes
                        };
                        // console.log(old_index, new_index);
                        if(currentGallery){
                            currentGallery = arrayMove(currentGallery, old_index, new_index);
                        } else {
                            currentGallery = arrayMove(galleryImagesArr, old_index, new_index);
                        }

                        if (gallery_id) {
                            $('.gallery-container[data-id='+gallery_id+']').find('.meta_images').val(JSON.stringify(currentGallery));
                        } else {
                            $('#images').val(JSON.stringify(currentGallery));
                        }
                    },
                });
            });

        });

    </script>
    <!-- Sortable -->
    <script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>

    <script>
        $(document).ready(function () {
            $('.vendor-select').on('change', function(){
                let vendor =  $(this).val();
                $(this).closest('.tab-pane').find('.apend-data').empty()
                let data = '  <tr>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][]"  disabled class="form-control" placeholder="Currency" value="AMD" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][price]"   class="form-control" placeholder="Price"  style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][percent][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][percent][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][deadline][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][deadline][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][fee][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][fee][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][insurance][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][amd][insurance][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                        </tr>\n' +
                    '                        <tr>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][]"  disabled class="form-control" placeholder="Currency" value="usd" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][price]"   class="form-control" placeholder="Price"  style="width:100%">\n' +

                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][percent][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][percent][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][deadline][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][deadline][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][fee][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][fee][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][insurance][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][usd][insurance][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                        </tr>\n' +
                    '                        <tr>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][]"  disabled class="form-control" placeholder="Currency" value="eur" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][price]"   class="form-control" placeholder="Price"  style="width:100%">\n' +

                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][percent][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][percent][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][deadline][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][deadline][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][fee][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][fee][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][insurance][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][eur][insurance][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                        </tr>\n' +
                    '                        <tr>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][]"  disabled class="form-control" placeholder="Currency" value="rub" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][price]"   class="form-control" placeholder="Price"  style="width:100%">\n' +

                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][percent][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][percent][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][deadline][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][deadline][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][fee][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][fee][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][insurance][min]"  class="form-control" placeholder="min" style="width:100%">\n' +
                    '                                <input type="text" name="vendor['+vendor+'][rub][insurance][max]"  class="form-control" placeholder="max" style="width:100%">\n' +
                    '                            </td>\n' +
                    '                        </tr>';

                $(this).closest('.tab-pane').find('.apend-data').append(data);
           });


        })
    </script>

@endsection()


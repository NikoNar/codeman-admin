<div class="box-group" id="accordion-v-{{ $i }}">
    <div class="panel box box-primary">
        <div class="box-header with-border" class="collapsed">
            <div class="col-md-1">
                <label for="">No</label>
                <h5>#{{$i+1}}</h5>
            </div>
            
            <div class="col-md-9">
                @foreach($options_grouped as $k => $group)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">{{ $group[0]['group_name'] }}</label>
                            <select name="variation[{{ $i }}][options][{{$k}}]" id="" class="attribute-group-id form-control select2">
                                @foreach($group as $option)
                                    @php
                                        $is_selected = '';
                                    @endphp
                                    @if(isset($variation) && !empty($variation))
                                        @foreach($variation as $v)
                                                
                                            @if($option['product_option_id'] == $v['product_option_id'])
                                                @php
                                                    $is_selected = 'selected="selected"';
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    <option value="{{ $option['product_option_id'] }}" {{ $is_selected }}>
                                        {{ $option['option_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-2 text-right">
                <label for=""></label>
                <div class="form-group">
                    <button class="btn btn-icon btn-xs btn-danger delete-box-group remove-variation" type="button" data-id="{{ $i }}">
                        <i class="fa fa-trash"></i> Remove 
                    </button>  
                    <a class="btn btn-icon btn-xs btn-fefault" data-toggle="collapse" data-parent="#accordion-v-{{ $i }}" href="#panel-v-{{ $i }}" aria-expanded="false" class="collapsed">
                        <i class="fa fa-sort-down" style="font-size: 20px;line-height: 10px;"></i>
                    </a>
                </div>
            </div>
        </div>
        <div id="panel-v-{{ $i }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body">
                <div class="col-md-4">

                    <div class="form-group">
                        {!! Form::label('variation_image') !!}
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-preview thumbnail" style="width: 100%;">
                                @if(isset($variation[0]) && isset($variation[0]['thumbnail']))
                                    <img src="{{ img_icon_size($variation[0]['thumbnail']) }}" class="img-responsive thumbnail-image" alt="" onerror="imgError(this);">
                                @else
                                    <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive thumbnail-image" alt="No Variation Image" onerror="imgError(this);">
                                @endif
                            </div>
                            <div>
                                <span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
                                    <span class="fileupload-new">Select image</span>
                                    {{ Form::hidden('variation['.$i.'][thumbnail]', 
                                    isset($variation[0]['thumbnail']) ? $variation[0]['thumbnail']: null,
                                     ['class' => 'thumbnail']) }}
                                </span>
                                <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload" >Remove</a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-8">
                    {!! Form::hidden('variation['.$i.'][id]',
                        isset($variation[0]['id']) ? $variation[0]['id'] : null) !!}
                    <div class="form-group">
                        <label for="variation[{{ $i }}][sku]">SKU</label>
                        {!! Form::text('variation['.$i.'][sku]',
                            isset($variation[0]['sku']) ? $variation[0]['sku'] : null,
                            [
                                'class' => 'form-control w-100',
                                'id' => 'variation['.$i.'][sku]'
                            ]) !!}
                    </div>
                    <div class="form-group">
                        <label for="variation[{{ $i }}][price]">Price</label>
                        {!! Form::text('variation['.$i.'][price]', 
                            isset($variation[0]['price']) ? $variation[0]['price'] : null, 
                            [
                                'class' => 'form-control w-100',
                                'id' => 'variation['.$i.'][price]'
                            ]) !!}
                    </div>
                    <div class="form-group">
                        <label for="variation[{{ $i }}][sale_price]">Sale Price</label>
                        {!! Form::text('variation['.$i.'][sale_price]',
                            isset($variation[0]['sale_price']) ? $variation[0]['sale_price'] : null,
                            [
                                'class' => 'form-control w-100',
                                'id' => 'variation['.$i.'][sale_price]'
                            ]) !!}
                    </div>
                    <div class='form-group'>
                        <label for="variation[{{ $i }}][stock_status]">Stock Status</label>
                        {!! Form::select('variation['.$i.'][stock_status]', 
                            [1 => 'In Stock', 0 => 'Out of Stock'],
                            isset($variation[0]['stock_status']) ? $variation[0]['stock_status'] : null,
                            ['class' => 'form-control select2 w-100']) !!}
                    </div>
                    <div class='form-group'>
                        <label for="variation[{{ $i }}][stock_count]">Stock Count</label>
                        {!! Form::text('variation['.$i.'][stock_count]', 
                            isset($variation[0]['stock_count']) ? $variation[0]['stock_count'] : null, 
                            [
                                'class' => 'form-control select2 w-100',
                                'id' => 'variation['.$i.'][stock_count]'
                            ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
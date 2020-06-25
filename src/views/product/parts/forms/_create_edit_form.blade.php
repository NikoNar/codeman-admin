@if(isset($product)  && !isset($parent_lang_id))
    {!! Form::model($product, ['url' => ["admin/product",$product->id ], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
    {!! Form::hidden('id', $product->id) !!}
@elseif(isset($product) && isset($parent_lang_id) )
    {!! Form::model($product, ['url' => "admin/product", 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'product-store']) !!}
    {!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
    {!! Form::open(['url' => "admin/product", 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif
<div class="col-md-9 border-right">
    <div class="form-group">
        {!! Form::label('title', 'Title') !!}
        <div class='input-group'>
            <span class="input-group-addon">
                <span class="fa fa-font"></span>
            </span>
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12 no-padding">
            {!! Form::label('slug', 'Slug'); !!}
            <div class='input-group'>
				<span class="input-group-addon">
					@if(isset($product))
                        <a href="{!! url(isset($lang) ? $lang : null .'product/'.$product->slug) !!}" target="_blank">
							<i class="fa fa-link"></i>
						</a>
                    @else
                        <span class="fa fa-link"></span>
                    @endif
				</span>
                <span class="input-group-addon no-border-right">
					<i>
						@if(isset($product))
                            {{ URL::to(isset($lang) ? $lang : null .'product/'.buildUrl($product, array(), false)) }}
                        @else
                            {{ URL::to(isset($lang) ? $lang : null .'product/') }}
                        @endif
					/</i>
				</span>
                @if(isset($slugEdit) && $slugEdit == false)
                    {!! Form::text('slug', null, ['class' => 'form-control', 'readonly']) !!}
                @else
                    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <br>
    <div class="form-group">

        {!! Form::label('description', 'Content'); !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}
        <br>
        {!! Form::label('short_description', 'Shord Description'); !!}
        {!! Form::textarea('short_description', null, ['class' => 'form-control', 'id' => 'short_description_editor']); !!}
        <br>
        {!! Form::label('code', 'Code'); !!}
        {!! Form::text('code', null, ['class' => 'form-control', 'id' => 'code']); !!}
        <br>
        {!! Form::label('youtube', 'Youtube Video id'); !!}
        {!! Form::text('youtube', null, ['class' => 'form-control', 'id' => 'youtube']); !!}

       
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="box">
        <div class="box-header with-border">
            <ul class="nav nav-tabs">
                @for($i=0; $i <= 4; $i++)
                    <li class="">
                        <a data-toggle="tab" href="#vendor{{ $i }}">
                            @if(isset($product_vendors) && isset($product_vendors[$i]))
                                {{ $product_vendors[$i]->title }}
                            @else
                                Vendor {{ $i + 1 }}
                            @endif
                        </a>
                    </li>
                @endfor
            </ul>
        </div>

        <div class="box-body">
            <div class="tab-content">
                @for($i=0; $i <= 4; $i++)
                    <div id="vendor{{ $i }}" class="tab-pane">
                        <div class="row">
                        <div class="form-group col-md-3">
                            @if(isset($vendors) && !empty($vendors))
                                {!! Form::label('', 'Vendor'); !!}
                                {!! Form::select('',  $vendors, isset($product_vendors) && isset($product_vendors[$i]) ? $product_vendors[$i]->id : null, ['class' => 'form-control select2 vendor-select', 'placeholder' =>'select vendor']); !!}
                            @endif
                        </div>
                        </div>
                        <table class="table">
                            <thead>
                                <th>Currency/price</th>
                                <th>Prepayment</th>
                                <th>Anual Rate</th>
                                <th>Term</th>
                                <th>Fee</th>
                                <th>Insurance</th>
                            </thead>
                            <tbody class="apend-data">
                                @if(isset($product_vendors) && !$product_vendors->isEmpty() && isset($product_vendors[$i]))

                                    @include('admin-panel::product.parts.vendor_tab', ['vendor' => $product_vendors[$i]])
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <hr>
    <div class="clearfix"></div>
</div>
<div class="col-md-3 right-sidebar">
    <div class="form-group">
        {!! Form::label('created_at', 'Published Date'); !!}
        <div class="clearfix"></div>
        <div class='input-group col-md-6 pull-left'>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
            {!! Form::text('published_date', null, ['class' => 'form-control', 'id' => 'datepicker']) !!}
        </div>
        <div class="input-group bootstrap-timepicker col-md-6 pull-left">
            {!! Form::text('published_time', null, ['class' => 'form-control timepicker', 'id' => 'timepicker']) !!}
            <div class="input-group-addon">
                <i class="fa fa-clock"></i>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        {!! Form::label('status', 'Status'); !!}
        {!! Form::select('status', ['published' => 'Published', 'unpublished' => 'Unpublished', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
    </div>
    <div class="form-group">
        @if(isset($languages) && !empty($languages))
            @isset($product)
                <input type="hidden" name="product_id" value="{{$product->id}}">
            @endif
            {!! Form::label('lang', 'Language'); !!}
            {!! Form::select('lang', $languages, isset($lang) ? $lang : null, ['class' => 'form-control select2 languages-product']); !!}
        @endif
    </div>
    <div class="col-md-12 no-padding">
        {!! Form::label('categories', 'Categories') !!}
        <div class='input-group'>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-tag"></span>
            </span>

            @if(isset($product) &&  null != $product_categories = $product->categories()->get()->pluck('id')->toArray())
                @if(!empty($product_categories))
                    @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple', 'selected' => $product_categories])
                @else
                    @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
                @endif
            @else
                @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
            @endif
        </div>
        <div class="clearfix"></div>
        <a href="#" class="pull-right" id="add-category" data-type="product"><i class="fa fa-plus"></i> Add New Category</a>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 no-padding">
        {!! Form::label('brand', 'Brand') !!}
        <div class='input-group'>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-equalizer"></span>
            </span>

            {{ Form::text('brand', null, ['class' => 'form-control', 'id' => 'brand', 'list' => "brands_list"]) }}
            <datalist id="brands_list">
            @if(isset($brands) && !empty($brands))
                @foreach($brands as $brand)
                    <option value="{{ $brand }}">
                @endforeach
            @endif
          </datalist>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group">
        {!! Form::label('thumbnail', 'Featured Image'); !!}
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-preview thumbnail" style="width: 100%;">
                @if(isset($product) && !empty($product->thumbnail))
                    <img src="{{$product->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
                @else
                    <img src="{{asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
                @endif
            </div>
            <div>
                <span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
                    <span class="fileupload-new">Select image</span>
                    {{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
                    {!! Form::hidden('thumbnail', null, ['id' => 'thumbnail']); !!}
                </span>
                <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="">
        @isset($product)
            @include('admin-panel::resource.parts.options.gallery', ['resource' => $product, 'id'=> 1, 'name'=>'gallery'])
                @else
            @include('admin-panel::resource.parts.options.gallery', ['resource' => null, 'id'=> 1, 'name'=>'gallery'])
        @endif
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="form-group">
        @if(isset($product))
            {!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
        @else
            {!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
        @endif
    </div>
</div>


{!! Form::close() !!}

@if(isset($offer)  && !isset($parent_lang_id))
    {!! Form::model($offer, ['url' => ["admin/offer",$offer->id ], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
    {!! Form::hidden('id', $offer->id) !!}
@elseif(isset($offer) && isset($parent_lang_id) )
    {!! Form::model($offer, ['url' => "admin/offer", 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'offer-store']) !!}
    {!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
    {!! Form::open(['url' => "admin/offer", 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
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
					@if(isset($offer))
                        <a href="{!! url(isset($lang) ? $lang : null .'product/'.$offer->slug) !!}" target="_blank">
							<i class="fa fa-link"></i>
						</a>
                    @else
                        <span class="fa fa-link"></span>
                    @endif
				</span>
                <span class="input-group-addon no-border-right">
					<i>
						@if(isset($offer))
                            {{ URL::to(isset($lang) ? $lang : null .'product/'.buildUrl($offer, array(), false)) }}
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

        {!! Form::label('description', 'Descrption'); !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'editor']); !!}
        <br>
        {!! Form::label('short_description', 'Shord Description'); !!}
        {!! Form::textarea('short_description', null, ['class' => 'form-control', 'id' => 'short_description_editor']); !!}

    </div>
    <div class="form-group">
        {!! Form::label('vendor', 'Vendor') !!}
        {!! Form::select('vendor', $vendors,  null, ['class' => 'form-control vendor-select', 'placeholder'=>'Select Vendor']) !!}
    </div>    <hr>
    <div class="form-group">
{{--        <label>Products</label>--}}
{{--        <select name="products[]" id="product-select" class="form-control" multiple>--}}
{{--        </select>--}}
        @isset($products)
        {!! Form::label('products[]', 'Products') !!}
        {!! Form::select('products[]',  $products,  $selected_products ? $selected_products : null, ['class' => 'form-control', 'id' =>'product-select', 'multiple']) !!}
        @else
            {!! Form::label('products[]', 'Products') !!}
            {!! Form::select('products[]', [], null,  ['class' => 'form-control', 'id' =>'product-select', 'multiple']) !!}
        @endif
    </div>
    <div class="form-group">
        {!! Form::label('categories[]', 'Categories') !!}
        {!! Form::select('categories[]', $categories, isset($selected_categories) ? $selected_categories :  null, ['class' => 'form-control', 'multiple']) !!}
    </div>    <hr>
    <div class="clearfix"></div>
</div>
<div class="col-md-3">
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
            @isset($offer)
                <input type="hidden" name="offer_id" value="{{$offer->id}}">
            @endif
            {!! Form::label('lang', 'Language'); !!}
            {!! Form::select('lang', $languages, isset($lang) ? $lang : null, ['class' => 'form-control select2 languages-offer']); !!}
        @endif
    </div>
    {{-- <div class="form-group">
        {!! Form::label('lang', 'Language'); !!}
        @if(isset($parent_lang_id) || (isset($offer) && $offer->lang == 'arm'))
            {!! Form::select('lang', ['arm' => 'Հայերեն'], null, ['class' => 'form-control select2', 'readonly']); !!}
        @else
            {!! Form::select('lang', ['en' => 'English'], null, ['class' => 'form-control select2', 'readonly']); !!}

        @endif
    </div> --}}
    <div class="clearfix"></div>

    <div class="form-group">
        {!! Form::label('thumbnail', 'Featured Image'); !!}
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-preview thumbnail" style="width: 100%;">
                @if(isset($offer) && !empty($offer->thumbnail))
                    <img src="{{$offer->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
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
    <hr>
    <div class="clearfix"></div>
    <div class="form-group">
        @if(isset($offer))
            {!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
        @else
            {!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
        @endif
    </div>
</div>


{!! Form::close() !!}

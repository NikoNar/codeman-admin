<div class="col-md-3 pull-right no-padding" style="margin-left: 15px;">
    <div class="input-group">
        <div class="input-group-addon input-group-blue">
            <i class="fa fa-rss"></i>
        </div>
        <select name="brand_name" id="filter-by-brand" class="form-control pull-left">
            <option value="">All Brand</option>
            @foreach( $brands as $brand)
                <option value="{{$brand}}" @if(isset($brands) && request()->has('brand_name') && request()->get('brand_name') == $brand) selected @endif> {!! $brand !!}</option>
            @endforeach
        </select>
    </div>
</div>
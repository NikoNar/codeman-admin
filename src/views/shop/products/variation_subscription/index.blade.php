@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
<div class="box">
    <div class="box-body">
        <div class="col-md-2 no-padding-right">
            <div class="form-group">
                <select name="per-page" id="table-perpage" class="form-control w-100"
                        data-placeholder="Show Per Page">
                    <option value="10" {{ request()->get('length') == '10' ? 'selected' : null }}>10</option>
                    <option value="25" {{ request()->get('length') == '25' ? 'selected' : null }}>25</option>
                    <option value="50" {{ request()->get('length') == '50' ? 'selected' : null }}>50</option>
                    <option value="100" {{ request()->get('length') == '100' ? 'selected' : null }}>100</option>
                    <option value="-1" {{ request()->get('length') == '-1' ? 'selected' : null }}>Show all</option>
                </select>
            </div>
        </div>
        @if(isset($products) && !empty($products))
            <div class="col-md-2 no-padding-right">
                <div class="form-group">
                    {!! Form::select('product', ['' => 'Select Product' ] + $products , isset($product) ? $product : null, ['class' => 'form-control select2', 'id' => 'table-product']) !!}
                </div>
            </div>
        @endif
        <div class="col-md-3 pull-right no-padding" >
            <div class="input-group">
                <input type="text" name="search" id="table-search" placeholder="Search" class="form-control" value="{!! request()->has('search') ? request()->get('search')['value'] : '' !!}">
                <input type="hidden" name="search_by" value="*">
                <div class="input-group-addon input-group-blue">
                    <i class="fa fa-search"></i>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="resource-container">
            @include('admin-panel::shop.products.variation_subscription.subscription_table')
        </div>

    </div>
    <!-- /.box-footer-->
</div>

@endsection

@section('script')
    @include('admin-panel::shop.products.data-table-js.t_subscription_table')
@endsection

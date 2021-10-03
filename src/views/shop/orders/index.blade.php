@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-body">
	    	<div class="col-md-3 no-padding-left">
	    		<span class="h4 text-left">Orders</span>
	    		{{-- <a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left btn-medium" id="resource-bulk-action"><i class="fa fa-trash"></i> Bulk Delete </a> --}}
	    		{{-- <div class="form-group col-md-7">
					<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">
						<option value="">Do Nothing</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div> --}}
	    	</div>
	    	<div class="col-md-9 no-padding">
	    		{{-- @include('admin-panel::layouts.resource_filter') --}}
	    		<form id="listing-filter">
	    			<div class="col-md-6 pull-right no-padding" >
	    		        <div class="input-group">
	    		    		<input type="text" name="search" id="resource-search" placeholder="Search" class="form-control" value="{!! request()->has('search') ? request()->get('search') : '' !!}">
	    		    		<input type="hidden" name="search_by" value="*">
	    		    		<div class="input-group-addon input-group-blue">
	    		        		<i class="fa fa-search"></i>
	    		        	</div>
	    		        </div>
	    			</div>
	    		</form>

    		</div>
    		<div class="clearfix"></div>
    		<br>
    		<div id="resource-container">
    			@include('admin-panel::shop.orders.parts.listing')
    		</div>
			{{-- <input type="hidden" id="resource_type" value="products"> --}}
			{{-- <input type="hidden" name="modelName" id="modelName" value="Product" > --}}
			<input type="hidden" name="view_direction" id="viewDirection" value="/admin/orders/" >
			
			<input type="hidden" name="collection_name" id="collection_name" value="resources" >
			<input type="hidden" name="modelName" id="modelName" value="\App\Models\{{Str::singular(ucwords($module))}}" >
			<input type="hidden" name="view_path" id="view_path" value="{{ 'admin.orders' }}" >
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')
					
	<!-- DataTables -->
	<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

@endsection
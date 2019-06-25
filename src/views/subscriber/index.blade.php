@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
	<div class="col-md-10 col-md-offset-1">
		<h3>Subscribers</h3>
		<div class="box">
		    <div class="box-body">
		    	<div class="col-md-4 no-padding-left">
		    		<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left btn-medium" id="resource-bulk-action"><i class="fa fa-trash"></i> Bulk Delete </a>
		    		{{-- <div class="form-group col-md-7">
						<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">
							<option value="">Do Nothing</option>
							<option value="bulk-delete">Delete</option>
						</select>
		    		</div> --}}
		    	</div>
		    	<div class="col-md-8 no-padding pull-right">
		    		{{-- <a href="#" class="btn btn-primary btn-flat  btn-medium pull-right">Add New Subscriber</a> --}}
		    		<form id="listing-filter">
		    			<div class="input-group col-md-3 pull-right margin-right-15 ">
		    				<input type="text" name="email-search" id="email-search" placeholder="Search By Email" class="form-control">
		    				<div class="input-group-addon input-group-blue">
		    		    		<i class="fa fa-search"></i>
		    		    	</div>
		    			</div>
		    		    <div class="col-md-3 pull-right">
		    		        <div class="input-group">
		    		            <div class="input-group-addon input-group-blue">
		    		                <i class="fa fa-shield"></i>
		    		            </div>
		    		            <select name="status" id="status" class="form-control pull-left">
		    		                <option value="">All</option>
		    		                <option value="subscribed"> Subscribed</option>
		    		                <option value="unsubscribed"> Unsubscribed</option>
		    		            </select>
		    		        </div>
		    		    </div>
		    		</form>
					
	    		</div>
	    		<div class="clearfix"></div>
	    		
	    		<div id="resource-container">
	    			@include('admin-panel::subscriber.parts.listing')
	    		</div>
		    	
				<input type="hidden" name="modelName" id="modelName" value="{!! isset($subscribers) && !$subscribers->isEmpty() ? class_basename($subscribers[0]) : null !!}" >

		    </div>
		    <!-- /.box-footer-->
		</div>
	</div>
	<div class="clearfix"></div>
@endsection

@section('script')
					
	<!-- DataTables -->
	<script src="{{ asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

@endsection
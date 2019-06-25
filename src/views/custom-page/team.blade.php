@extends('admin.layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-body">
	    	<div class="col-md-4 pull-left no-padding "> 
	    		<div class="form-group col-md-7 no-padding ">
	    			<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left btn-medium" id="resource-bulk-action"> <i class="fa fa-trash"></i> Bulk Delete</a>
	    		</div>	
	    		{{-- <div class="form-group col-md-7">
					<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">
						<option value="">Do Nothing</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div> --}}
	    	</div>
	    	<div class="col-md-8 no-padding pull-right">
	    		<a href="{{ action("Admin\CustomPagesController@teamCreate") }}" class="btn btn-primary btn-flat pull-right ">Add New Member</a>
	    		@include('admin.layouts.resource_filter', ['languages' => true])
	    	</div>
    		<div id="resource-container">
    			@include('admin.team.parts.listing')
    		</div>
			<input type="hidden" name="modelName" id="modelName" value="{!! isset($teams) && !$teams->isEmpty() ? class_basename($teams[0]) : null !!}" >
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')
<!-- DataTables -->
<script src="{{ asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>

@endsection
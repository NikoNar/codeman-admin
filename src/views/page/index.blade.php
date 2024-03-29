@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-body">
	    	<div class="col-md-4 no-padding-left">
	    		{{-- <a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left btn-medium" id="resource-bulk-action"><i class="fa fa-trash"></i> Bulk Delete </a> --}}
	    		{{-- <div class="form-group col-md-7">
					<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">
						<option value="">Do Nothing</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div> --}}
	    	</div>
	    	<div class="col-md-8 no-padding pull-right">
	    		<a href="{{ route('page-create') }}" class="btn btn-primary btn-flat pull-right ">Create New Page</a>
	    		@include('admin-panel::layouts.resource_filter')
	    	</div>
	    	<div class="clearfix"></div>
    		<div id="resource-container">
    			@include('admin-panel::page.parts.listing')
    		</div>
			<input type="hidden" name="modelName" id="modelName" value="Page" >
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')
<!-- DataTables -->
{{-- <script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
 --}}
@endsection
@extends('admin-panel::layouts.app')
@section('style')
{{--	{{dd($resources)}}--}}
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-body">
	    	<div class="col-md-3 no-padding-left">
	    		<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left btn-medium" id="resource-bulk-action"><i class="fa fa-trash"></i> Bulk Delete </a>
	    		{{-- <div class="form-group col-md-7">
					<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">
						<option value="">Do Nothing</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div> --}}
	    	</div>
	    	<div class="col-md-9 no-padding pull-right">
	    		<a href="{{ url("admin/resource/$module/create") }}" class="btn btn-primary btn-flat  btn-medium pull-right">Create {{Str::singular(ucwords($module))}}</a>
	    		@include('admin-panel::layouts.resource_filter')
				
    		</div>
    		<div class="clearfix"></div>
    		
    		<div id="resource-container">
    			@include('admin-panel::resource.parts.listing')
    		</div>
			<input type="hidden" id="resource_type" value="{{$module}}">
			<input type="hidden" name="modelName" id="modelName" value="Resource" >

	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')
					
	<!-- DataTables -->
	<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

@endsection
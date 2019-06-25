{{--@extends('admin-panel::layouts.app')--}}
{{--@section('style')--}}
{{--	<!-- DataTables -->--}}
{{--	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">--}}
{{--	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">--}}
{{--	<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">--}}
{{--	<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">--}}
{{--	<link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">--}}
{{--	--}}
{{--	--}}
{{--@endsection--}}
{{--@section('content')--}}
{{--	<div class="box">--}}
{{--	    <div class="box-body">--}}
{{--	    	<div class="col-md-4 no-padding-left">--}}
{{--	    		<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left btn-medium" id="resource-bulk-action"><i class="fa fa-trash"></i> Bulk Delete </a>--}}
{{--	    		--}}{{-- <div class="form-group col-md-7">--}}
{{--					<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">--}}
{{--						<option value="">Do Nothing</option>--}}
{{--						<option value="bulk-delete">Delete</option>--}}
{{--					</select>--}}
{{--	    		</div> --}}
{{--	    	</div>--}}
{{--	    	<div class="col-md-8 no-padding pull-right">--}}
{{--	    		<a href="#" class="btn btn-primary btn-flat pull-right" id="add-category" data-type="{!! $type !!}">Add New Category</a>--}}
{{--	    		--}}{{-- @include('admin-panel::layouts.resource_filter') --}}
{{--	    	</div>--}}
{{--	    	<div class="clearfix"></div>--}}
{{--    		<div id="resource-container">--}}
{{--    			@include('admin-panel::category.parts.listing')--}}
{{--    		</div>--}}
{{--			<input type="hidden" name="modelName" id="modelName" value="{!! isset($categories) && !$categories->isEmpty() ? class_basename($categories[0]) : null !!}" >--}}
{{--	    </div>--}}
{{--	    <!-- /.box-footer-->--}}
{{--	</div>--}}
{{--	--}}{{-- @include('admin-panel::category.create_edit') --}}
{{--@endsection--}}

@section('script')
<!-- DataTables -->
<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
{{-- <script src="//mpryvkin.github.io/jquery-datatables-row-reordering/1.2.2/jquery.dataTables.rowReordering.js"></script> --}}
{{-- <script src="{{ asset('admin-panel/bower_components/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/dataTables.editor.min.js') }}"></script> --}}
@endsection


@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
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
				<a href="{{ route('categories.create', [$type]) }}" class="btn btn-primary btn-flat  btn-medium pull-right">Create Category</a>
				@include('admin-panel::layouts.resource_filter')

			</div>
			<div class="clearfix"></div>

			<div id="resource-container">
				@include('admin-panel::category.parts.listing')
			</div>

			<input type="hidden" name="modelName" id="modelName" value="{!! isset($categories) && !$categories->isEmpty() ? class_basename($categories[0]) : null !!}" >

		</div>
		<!-- /.box-footer-->
	</div>
@endsection

@section('script')

	<!-- DataTables -->
	<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

@endsection
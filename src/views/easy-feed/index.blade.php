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
	    	</div>
	    	<div class="col-md-8 no-padding pull-right">
	    		<a href="{{ route('easy-feed-create') }}" class="btn btn-primary btn-flat pull-right ">Create New Feed</a>
	    		@include('admin-panel::layouts.resource_filter')
	    	</div>
	    	<div class="clearfix"></div>
    		<div id="resource-container">
				<table id="sortable-table" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
							<th>Name</th>
							<th>Url</th>
							
							<th width="12%" class="text-center">Created Date</th>
							<th width="10%" class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($easyFeedArr as $key => $feed)
							<tr data-id="{{ $feed->id }}">
								<td><input type="checkbox" name="checked" value="{{ $feed->id }}"></td>
								<td>
									{{ $feed->brand_name }}
								</td>
								<td>
								{{ $feed->url }}
								<td class="text-center">{{ date('m/d/Y g:i A', strtotime($feed->created_at)) }}</td>
								<td class="action text-center">
									<a href="{{ route('easy-feed-destroy', $feed->id ) }}" title="Delete" class="btn btn-xs delete-feed btn-danger"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="clearfix"></div>
    		</div>
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')
<!-- DataTables -->
<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$('body').off('click', '.delete-feed').on('click', '.delete-feed', function(e){
			(!confirm("Delete the feed?")) ? e.preventDefault() : null;
			
		});
	});
</script>

alert();
@endsection
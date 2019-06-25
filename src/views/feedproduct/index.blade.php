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
	    		{{-- <a href="{{ route('easy-feed-create') }}" class="btn btn-primary btn-flat pull-right ">Create New Feed</a> --}}
	    		@include('admin-panel::layouts.resource_filter', ['search_by' => 'name'])
	    	</div>
	    	<div class="clearfix"></div>
    		<div id="resource-container">
				@include('admin-panel::feedproduct.parts.listing')
    		</div>
    		<input type="hidden" name="modelName" id="modelName" value="{!! isset($feedproducts) && !$feedproducts->isEmpty() ? class_basename($feedproducts[0]) : null !!}" >
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
		$('body').off('click', '.delete-product').on('click', '.delete-product', function(e){
			(!confirm("Delete product?")) ? e.preventDefault() : null;
			
		});

		$('body').off('click', '.status').on('click', '.status', function(e){
			 e.preventDefault();
			 let status = $(this).hasClass('active') ? 'non_active' : 'active';  // Reverse status
			 let id = $(this).data('id');
			 let $this = $(this);
			 let el = $('#product_status-'+id);
			 console.log(el);
			 if (confirm("Change status?")) {


			 	$.ajax({
			 		url: "{{route('feed-product-status')}}",
			 		type: 'POST',
			 		data: {id: id, status: status},
			 	})
			 	.done(function(response) {
			 		// location.reload();
			 		console.log(status);
			 		if(status == 'non_active'){
			 			el.removeAttr('checked');
			 			$this.removeClass('active').addClass('non_active');
			 		}else{
			 			$this.removeClass('non_active').addClass('active');
			 			el.attr('checked', 1);
			 		}

			 	})
			 	.fail(function() {
			 		console.log("error");
			 	});
			 	
			 }
			
		});
	});
</script>

alert();
@endsection
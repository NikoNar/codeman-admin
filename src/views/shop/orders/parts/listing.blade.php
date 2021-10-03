<table class="table table-bordered table-striped">
	<thead>
		<tr>
			{{-- <th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th> --}}
					
			<th width="200px">Order ID</th>
			<th width="150px">Payment Method</th>
			<th width="130px">Shipping Mthod</th>
			<th width="60px">Status</th>
			<th width="140px">Date</th>
			<th width="10%">Total Price</th>

			<th width="140px" class="text-center">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($resources as $key => $item)
			@include('admin-panel::shop.orders.parts.item', ['module' => 'orders'])
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $resources->perPage() * $resources->currentPage() !!} of {!! $resources->total() !!} entries</div></div>
<div class="pull-right ">{!! $resources->links() !!}</div>

{{-- {!! dd($resource_list) !!} --}}
{{-- {!! dd($resource_list->currentPage()) !!} --}}
{{-- {!! dd($resource_list->perPage()) !!} --}}
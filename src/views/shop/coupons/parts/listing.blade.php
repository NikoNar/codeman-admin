<table id="ajax-table" class="display table table-bordered table-striped table-responsive table-sortable actions-bar-fixed">
	<thead>
		<tr>
			<th class="no-sort text-center"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>code</th>
			<th>discount</th>
			<th>type</th>
			<th>start_date</th>
			<th>end_date</th>
			<th>usage_limit</th>
			<th>items_usage_limit</th>
			<th>user_usage_limit</th>
			<th>creator_id</th>
			<th>min_spend_amount</th>
			<th>max_spend_amount</th>
			<th>created_at</th>
			<th>updated_at</th>
			<th>status</th>
			<th>order</th>
		</tr>
	</thead>
	@isset($resources)
		<tbody>
			@foreach($resources as $key => $item)
				@include('admin-panel::shop.coupons.parts.item')
			@endforeach
		</tbody>
	@endif
	<tfoot>
		<tr>
			<th class="no-sort text-center"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>code</th>
			<th>discount</th>
			<th>type</th>
			<th>start_date</th>
			<th>end_date</th>
			<th>usage_limit</th>
			<th>items_usage_limit</th>
			<th>user_usage_limit</th>
			<th>creator_id</th>
			<th>min_spend_amount</th>
			<th>max_spend_amount</th>
			<th>created_at</th>
			<th>updated_at</th>
			<th>status</th>
			<th>order</th>
		</tr>
	</tfoot>
</table>
<div class="clearfix"></div>
@isset($resources)
	<div class="col-md-6 pull-left">
		<div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">
			Showing  to {!! $resources->perPage() * $resources->currentPage() !!} of {!! $resources->total() !!} entries
		</div>
	</div>
	<div class="pull-right ">{!! $resources->appends(request()->all())->links() !!}</div>
@endif
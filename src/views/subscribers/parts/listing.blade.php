<table id="ajax-table" class="display table table-bordered table-striped table-responsive table-sortable actions-bar-fixed w-100">
	<thead>
		<tr>
			<th class="no-sort text-center"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Email</th>
			<th>User</th>
			<th>Subscribed Date</th>
			<th>Unsubscribed Date</th>
			<th>Status</th>
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
			<th>Email</th>
			<th>User</th>
			<th>Subscribed Date</th>
			<th>Unsubscribed Date</th>
			<th>Status</th>
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
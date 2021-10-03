<table id="ajax-table" class="display table table-bordered table-striped table-responsive table-sortable actions-bar-fixed">
	<thead>
		<tr>
			<th class="no-sort text-center"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Image</th>
			<th>Title</th>
			{{-- <th>Sku</th> --}}
			<th>Price</th>
			{{-- <th>Sale Price</th> --}}
			<th>Categories</th>
			<th>Created Date</th>
			{{-- <th>Updated Date</th> --}}
			<th>Status</th>
			{{-- <th class="text-center">Actions</th> --}}
		</tr>
	</thead>
	@isset($resources)
		<tbody>

		</tbody>
	@endif
	<tfoot>
		<tr>
			<th class="no-sort text-center"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Image</th>
			<th>Title</th>
			{{-- <th>Sku</th> --}}
			<th>Price</th>
			{{-- <th>Sale Price</th> --}}
			<th>Categories</th>
			<th>Created Date</th>
			{{-- <th>Updated Date</th> --}}
			<th>Status</th>
			{{-- <th class="text-center">Actions</th> --}}
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
<input type="hidden" name="modelName" id="modelName" value="\Codeman\Admin\Models\Shop\Variation" >

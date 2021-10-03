<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th>Group Name</th>
			{{-- <th >Categories</th> --}}
			<th width="10%">Type</th>
			<th width="10%">Options</th>
			<th width="10%">Status</th>
			<th width="10%">Show on website</th>
			<th width="10%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="140px" class="text-center">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($resources as $key => $item)
			@include('admin-panel::shop.'.$module.'.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $resources->perPage() * $resources->currentPage() !!} of {!! $resources->total() !!} entries</div></div>
<div class="pull-right ">{!! $resources->links() !!}</div>

{{-- {!! dd($resource_list) !!} --}}
{{-- {!! dd($resource_list->currentPage()) !!} --}}
{{-- {!! dd($resource_list->perPage()) !!} --}}
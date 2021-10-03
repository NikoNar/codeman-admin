<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th width="20%">Role</th>
			<th width="15%">Description</th>
			<!-- <th width="25%">About</th> -->
			{{-- <th width="15%">Language</th> --}}
			<th width="8%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="12%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($roles as $key => $item)
			@include('admin-panel::roles.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $roles->perPage() * $roles->currentPage() !!} of {!! $roles->total() !!} entries</div></div>
<div class="pull-right ">{!! $roles->links() !!}</div>

{{-- {!! dd($role_list) !!} --}}
{{-- {!! dd($role_list->currentPage()) !!} --}}
{{-- {!! dd($role_list->perPage()) !!} --}}
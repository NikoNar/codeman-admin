<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th width="25%">Name</th>
			<th width="20%">Email</th>
			<th width="10%">Role</th>
			<th width="13%">Verified at</th>
			<th width="10%">Registered Date</th>
			<th width="220px">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($users as $key => $item)
			@include('admin-panel::user.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" user="status" aria-live="polite">Showing  to {!! $users->perPage() * $users->currentPage() !!} of {!! $users->total() !!} entries</div></div>
<div class="pull-right ">{!! $users->links() !!}</div>

{{-- {!! dd($user_list) !!} --}}
{{-- {!! dd($user_list->currentPage()) !!} --}}
{{-- {!! dd($user_list->perPage()) !!} --}}
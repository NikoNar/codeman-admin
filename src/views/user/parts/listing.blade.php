<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th width="20%">Name</th>
			<th width="15%">Email</th>
			<th width="15%">Role</th>

			
			<th width="12%">Verified at</th>
			<th width="10%">Registered Date</th>
			<th width="12%">Actions</th>
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
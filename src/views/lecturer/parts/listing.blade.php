<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th width="20%">Name</th>
			<th width="10%">Position</th>
			<th width="25%">LinkedIn</th>
			{{-- <th width="15%">Language</th> --}}
			<th width="10%">Status</th>
			<th width="10%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="12%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($lecturers as $key => $item)
			@include('admin-panel::lecturer.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $lecturers->perPage() * $lecturers->currentPage() !!} of {!! $lecturers->total() !!} entries</div></div>
<div class="pull-right ">{!! $lecturers->links() !!}</div>

{{-- {!! dd($lecturer_list) !!} --}}
{{-- {!! dd($lecturer_list->currentPage()) !!} --}}
{{-- {!! dd($lecturer_list->perPage()) !!} --}}
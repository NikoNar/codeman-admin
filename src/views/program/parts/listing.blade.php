<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th width="20%">Name</th>
			<th width="10%">Starts On</th>
			<th width="10%">Duration</th>
			<th width="10%">Price</th>
			<!-- <th width="25%">Desctiption</th> -->
			{{-- <th width="15%">Language</th> --}}
			<!-- <th width="10%">Status</th> -->
			<th width="8%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="13%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($programs as $key => $item)
			@include('admin-panel::program.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $programs->perPage() * $programs->currentPage() !!} of {!! $programs->total() !!} entries</div></div>
<div class="pull-right ">{!! $programs->links() !!}</div>

{{-- {!! dd($program_list) !!} --}}
{{-- {!! dd($program_list->currentPage()) !!} --}}
{{-- {!! dd($program_list->perPage()) !!} --}}
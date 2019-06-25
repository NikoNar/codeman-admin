<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th >Title</th>
			<th width="10%">Year</th>
{{--			<th width="25%">File</th>--}}
			 <th width="15%">Status</th>
			<th width="10%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="13%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($files as $key => $item)
			@include('admin-panel::file.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $files->perPage() * $files->currentPage() !!} of {!! $files->total() !!} entries</div></div>
<div class="pull-right ">{!! $files->links() !!}</div>

{{-- {!! dd($file_list) !!} --}}
{{-- {!! dd($file_list->currentPage()) !!} --}}
{{-- {!! dd($file_list->perPage()) !!} --}}
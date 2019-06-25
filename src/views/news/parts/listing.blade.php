<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
					
			<th width="30%">Title</th>
			<th width="25%">Url</th>
			{{-- <th width="15%">Language</th> --}}
			<th width="10%">Status</th>
			<th width="8%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="12%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($news as $key => $item)
			@include('admin-panel::news.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $news->perPage() * $news->currentPage() !!} of {!! $news->total() !!} entries</div></div>
<div class="pull-right ">{!! $news->links() !!}</div>

{{-- {!! dd($news_list) !!} --}}
{{-- {!! dd($news_list->currentPage()) !!} --}}
{{-- {!! dd($news_list->perPage()) !!} --}}
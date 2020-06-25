<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>

			<th width="30%">Title</th>
			<th width="25%">Url</th>
			{{-- <th width="15%">Language</th> --}}
			<th width="8%">Status</th>
			<th width="10%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="10%">Actions</th>
		</tr>
	</thead>
	<tbody>
    @isset($offers)
		@foreach($offers as $key => $item)
			@include('admin-panel::offer.parts.item')
		@endforeach
    @endif
	</tbody>
</table>
<div class="clearfix"></div>
@isset($offers)
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $offers->perPage() * $offers->currentPage() !!} of {!! $offers->total() !!} entries</div></div>
<div class="pull-right ">{!! $offers->links() !!}</div>
@endif
{{-- {!! dd($news_list) !!} --}}
{{-- {!! dd($news_list->currentPage()) !!} --}}
{{-- {!! dd($news_list->perPage()) !!} --}}

<table id="data-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th width="5%">#</th>
			<th>Email</th>
			<th width="15%">Status</th>
			<th width="18%">Subscribe Date</th>
			<th width="5%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($subscribers as $key => $item)
			@include('admin-panel::subscriber.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $subscribers->perPage() * $subscribers->currentPage() !!} of {!! $subscribers->total() !!} entries</div></div>
<div class="pull-right ">{!! $subscribers->links() !!}</div>
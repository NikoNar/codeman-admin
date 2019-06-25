<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Title</th>
			<th>Url</th>
			<th width="10%">Status</th>
			<th width="12%">Created Date</th>
			<th width="12%">Last Time Update</th>
			<th width="13%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($pages as $key => $page)
			@include('admin-panel::page.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
<div class="pull-right">{!! $pages->appends(request()->except('page'))->links() !!}</div>
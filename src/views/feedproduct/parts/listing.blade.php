@if(isset($feedproducts) && !$feedproducts->isEmpty())
<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th class="text-center">Thumbnail</th>
			<th>Name</th>
			<th>Brand Name</th>
			<th>UPC</th>
			<th>Merchant Name</th>
			<th width="12%">Last Time Update</th>
			<th width="18%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($feedproducts as $key => $product)
			@include('admin-panel::feedproduct.parts.item')
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
{{ $feedproducts->appends(request()->except('page'))->links() }}
@endif
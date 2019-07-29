<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort reorder"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Name</th>
			<th>Description</th>
			<th>Type</th>
			<th width="15%">Created Date</th>
			<th width="15%">Last Time Update</th>
			<th width="12%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($categories as $key => $category)
			@if(!$category->parent_id)
			@include('admin-panel::category.parts.item')
			@endif
			{{-- @php $level = 0 @endphp --}}
			{{-- @if($category->slug == 'մաշկի-խնամք')
			{!! dd($category->catChilds) !!} 
			@endif --}}
			@if(count($category->catChilds))
                @include('admin-panel::category.parts.childs-listing',['childs' => $category->catChilds, 'level' => 0])
            @endif
		@endforeach
	</tbody>
</table>
<div class="clearfix"></div>
{{-- <div class="pull-right">{!! $categories->links() !!}</div> --}}
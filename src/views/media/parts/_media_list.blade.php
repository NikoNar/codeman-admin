@if(isset($images) && !$images->isEmpty())
	@if(request()->has('view-mode') && request()->get('view-mode') == 'list')
		@foreach($images as $key => $image)
			@include('admin-panel::media.parts._media_item_list')
		@endforeach
	@else
		@foreach($images as $key => $image)
			@include('admin-panel::media.parts._media_item_grid')
		@endforeach
	@endif
	<div class="clearfix"></div>
	<hr>
	{{-- <div class="col-md-6 pull-left"><div class="dataTables_info" id="data-table_info" role="status" aria-live="polite">Showing  to {!! $images->perPage() * $images->currentPage() !!} of {!! $images->total() !!} entries</div></div> --}}
	<div class="pull-right ">
	@if(method_exists($images, 'links') && is_callable(array($images, 'links')))
	 	{!! $images->links() !!}
	@endif </div>
@endif
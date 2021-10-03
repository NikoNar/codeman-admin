<div class="box">
    <div class="box-body">
		<div class="box-header row">
	    	<form action="{{ route('variations.sort') }}">
		    	@if(isset($categories) && !empty($categories))
			    	<div class="col-md-3">
			    		@include('admin-panel::components.categories', [
			    			'noAddLink' => true,
			    			'noLabel' => true,	    			
			    			//'render' => true, 
		    				'selected' => request()->has('category_id') ? request()->get('category_id') : []
			    		])
			    	</div>
			    	<div class="col-md-1 no-padding-right">
			    		<button type="submit" class="btn btn-primary btn-flat">Filter</a>
			    	</div>
		    	@endif
	    	</form>
		</div>
		<div class="col-md-12">
	    	<p>Showing {{$variations->count()}} entries</p>
		</div>
		<div class="clearfix"></div>
    	<hr>
    	<div class="container">
			<div class="grid-sortable">
				@if(isset($variations) && !$variations->isEmpty())
					@foreach($variations as $item)
						@include('admin-panel::shop.variations.parts._sort_grid_item')
					@endforeach
				@endif
			</div>
    	</div>
    </div>
    <!-- /.box-footer-->
</div>
<div class="clearfix"></div>
<hr>
<div class="row">
	<div class="col-md-12">
		<div class="collapse" id="bulk-edit-collapse" style="margin-top: -20px;">
		  	<div class="card card-body">
				<div class="clearfix"></div>
				<div class="box" style="background-color: #f9f9f9;">
				    <div class="box-header with-border">
				        <h3 class="box-title">Bulk Edit</h3>
				        <div class="box-tools pull-right">
				            <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#bulk-edit-collapse" >
				            	<i class="fa fa-minus"></i>
				            </button>
				        </div>
				    </div>
				    <!-- /.box-header -->
				    <div class="box-body" >
				        <div class="row">
			    			<form action="{!! route('variations.bulk-edit') !!}" class="inline-form" id="bulk-edit-form" method="PUT">
			    				<div class="col-md-3">
			    					@include('admin-panel::components.select', [
										'id'		=>	time()+1, 
										'label' 	=> 	'Status', 
										'name' 		=> 	'status', 
										'type' 		=> 	'select', 
										'options'	=>	[
											null => 'Select Status',
											'published' => 'Published',
											'draft' => 'Draft',
											'pending' => 'Pending',
											'archive' => 'Archive',
											'deleted' => 'Deleted',
											'schedule' => 'Schedule',
										],
										'multiple'	=>	false,
										'info' => 'Will not be changed if nothing is selected',
									])
			    				</div>
	    				    	{{-- @if(isset($categories) && !empty($categories))
	    					    	<div class="col-md-3">
	    					    		@include('admin-panel::components.categories', [
	    					    			'noAddLink' => true,
	    					    			'placeholder' => 'Do No Change',
	    					    			//'render' => true, 
	    					    			//'selected' => request()->has('relations') ? request()->get('relations')['categories'] : []
	    					    		])
	    					    	</div>
	    				    	@endif --}}
	    				    	@if(isset($labels) && !empty($labels))
	    					    	<div class="col-md-3">
	    					    		@include('admin-panel::components.select', [
	    					    			'id' => time() + 1,
	    					    			'label' => 'Lables',
	    					    			'name' => 'label_ids[]',
	    					    			'noAddLink' => true,
	    					    			'placeholder' => 'Do No Change',
	    					    			'options' => $labels,
	    					    			'multiple' => 'true',
	    					    			//'render' => true, 
	    					    			//'selected' => request()->has('relations') ? request()->get('relations')['categories'] : []
	    					    			'info' => 'Will not be changed if nothing is selected',
	    					    		])
	    					    	</div>
	    				    	@endif
				    			<div class="col-md-12">
				    				<button type="submit" name="submit_filters" id="submit_filters" class="btn btn-primary btn-flat btn-md" style="padding-left:30px; padding-right:30px ">
				    					Apply changes to selected list 
				    				</button>
				    			</div>
			    			</form>
				        </div> <!-- /.col -->
				    </div> <!-- ./box-body -->
				</div>
    			<div class="clearfix"></div>
    			<hr>
		  	</div>
		</div>
	</div>
</div>
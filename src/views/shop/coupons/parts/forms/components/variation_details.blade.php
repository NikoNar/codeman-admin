<div class="box-header with-border">
    <h3 class="box-title">Product Details</h3>
</div>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
    	<li class="active"><a href="#general" data-toggle="tab" aria-expanded="true">General</a></li>
    	<li class=""><a href="#inventory" data-toggle="tab" aria-expanded="false">Inventory</a></li>
    	<li class=""><a href="#shipping" data-toggle="tab" aria-expanded="false">Shipping</a></li>
    	<li class=""><a href="#attributes" data-toggle="tab" aria-expanded="false">Attributes</a></li>
    </ul>
    <div class="tab-content">
    	<div class="tab-pane active" id="general">
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('product_name') !!}
            		</div>
                    @if(isset($resource) && isset($resource->product))
                		<div class="col-md-4">
    	        			<div class='input-group w-100'>
    	        				<input type="text" value="{{ $resource->product->title }}" disabled="disabled" class="form-control">
    	        			</div>
                		</div>
                    @else
                        <div class="col-md-4">
                            <div class='input-group w-100'>
                                {!! Form::select('product_id', ['' => 'Select Product'] + $products, null, ['class' => 'form_control']) !!}
                            </div>
                        </div>
                    @endif
    			</div>
    		</div>
    		@if(isset($brands) && !empty($brands))
    		<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('brand_id', 'Product Brand') !!}
            		</div>
            		<div class="col-md-4">
	        			<div class='input-group w-100'>
	        				{!! Form::select('brand_id', ['' => 'Select a Brand']+$brands, null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    		@endif
            @if(isset($sex_group))
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('type', 'Gender') !!}
            		</div>
            		<div class="col-md-4">
	        			<div class='input-group w-100'>
	        				{!! Form::select('sex', [null => 'None']+$sex_group, null, ['class' => 'form-control w-100']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
            @endif
    		@if(isset($promortion_type) && !empty($promortion_type))
    		<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('promortion_type', 'Promote as') !!}
            		</div>
            		<div class="col-md-4">
	        			<div class='input-group w-100'>
	        				{!! Form::select('promortion_type', [
	        					null => 'None',  
	        					'bestseller' => 'Bestseller',  
	        					'sale' => 'Sale',  
	        					'new' => 'New',  
	        					], null, ['class' => 'form-control w-100']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    		@endif
    		<hr>
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('price', 'Product Price') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('price', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
	    	<div class="form-group">
	    		<div class="row">
            		<div class="col-md-2">
						{!! Form::label('sale_price', 'Sale Price') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('sale_price', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
				</div>
			</div>

			<div class="form-group">
	    		<div class="row">
            		<div class="col-md-2">
						{!! Form::label('sale_percent', 'Sale Percent (%)') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('sale_percent', null, ['class' => 'form-control', 'disabled']) !!}
	        				
	        			</div>
            		</div>
				</div>
			</div>
    	</div>
    	<!-- /.tab-pane -->
    	<div class="tab-pane" id="inventory">
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('sku', 'SKU') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('sku', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    		<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('stock_status', 'Stock status') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::select('stock_status', [1 => 'In Stock', 0 => 'Out of Stock'], null, ['class' => 'form-control select2 w-100']); !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    		<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('stock_count', 'Stock count') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('stock_count', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    	</div>
    	<!-- /.tab-pane -->
    	<div class="tab-pane" id="shipping">
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('weight', 'Weight (kg)') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('weight', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('width', 'Width (cm)') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('width', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    		<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('height', 'Height (cm)') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('height', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    		<div class="form-group">
    			<div class="row">
            		<div class="col-md-2">
        				{!! Form::label('length', 'Length (cm)') !!}
            		</div>
            		<div class="col-md-10">
	        			<div class='input-group'>
	        				{!! Form::text('length', null, ['class' => 'form-control']) !!}
	        			</div>
            		</div>
    			</div>
    		</div>
    	</div>
    	<!-- /.tab-pane -->
    	<div class="tab-pane" id="attributes">
            @if(isset($option_groups))
        	<div class="form-group">
    			<div class="row">
            		<div class="col-md-3">
        				{!! Form::label('option_groups', 'Select Option Group') !!}
            		</div>
            		<div class="col-md-5">
	        			{!! Form::select('option_groups', $option_groups, null, ['class' => 'form-control', 'id' => 'option_groups_select']) !!}
            		</div>
            		<div class="col-md-4">
	        			<button class="btn btn-primary btn-flat" id="option_groups_add_btn" type="button">Add Group</button>
            		</div>
            		<div class="clearfix"></div>
    			</div>
    		</div>
            @endif
    		<div class="options-container">
    			@if(isset($selected_groups) && !empty($selected_groups))
					@foreach($selected_groups as $group)
						@include('admin-panel::shop.products.parts.attributes.item', [
							'group' => $group,
							'options' => $group->productOptions->pluck('name', 'id'),
							'selectd_group_options' => $selectd_group_options,
                            'single_choice' => true
						])
					@endforeach
    			@endif
    		</div>
    	</div>
    	<!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
<div class="col-md-12">
	<h5 style="margin-top: 0">Filter</h5>
</div>
@if(isset($categories) && !empty($categories))
	<div class="col-md-3 no-padding-right">
		@include('admin-panel::components.categories', [
			'noAddLink' => true,
		])
	</div>
@endif
@if(isset($products) && !empty($products))
	<div class="col-md-3 no-padding-right">
	    <div class="form-group">
	    	<label for="">Products</label>
	    	{!! Form::select('product', ['' => 'Select Product' ] + $products , isset($product) ? $product : null, ['class' => 'form-control select2', 'id' => 'table-product']) !!}
	    </div>
	</div>
@endif

<div class="col-md-3 no-padding-right datepicker" style="padding-top: 0; padding-left: 15px">
    @include('admin-panel::components.datetimepicker', [
    	'id' => time(),
    	'label' => 	'Created Date', 
    	'name' 	=> 	'created_at',
    ])
</div>

<div class="clearfix"></div>
@if(isset($product_options_grouped) && !empty($product_options_grouped))
	@foreach($product_options_grouped as $key => $group)
		@php
			$options = [];
			$selected_options = [];
			foreach($group['product_options'] as $option){
				$options[$option['id']] = $option['name'];
			}
			
			$request_option_groups = request()->has('relations') &&  isset(request()->get('relations')['option_groups']) ? request()->get('relations')['option_groups'] : [];

			$arr_key = array_search('product_option_groups['.$group['id'].']', array_column($request_option_groups, 'name'));
			if($key !== false){
				$selected_options = isset($request_option_groups[$arr_key]['value']) ? $request_option_groups[$arr_key]['value'] : [];
			}
		@endphp

		<div class="col-md-3 no-padding-right">
			@include('admin-panel::components.select', [
				'id'		=>	$key.'-'.$group['id'], 
				'label' 	=> 	$group['name'], 
				'placeholder' => 'Select '.$group['name'],
				'name' 		=> 	'product_option_groups['.$group['id'].'][]', 
				'type' 		=> 	'select', 
				'options'	=>	$options, 
				'multiple'	=>	true,
				'info' 		=> isset($val['info']) ? $val['info'] : null,
				'selected' => $selected_options
			])
		</div>
	@endforeach
@endif
<div class="clearfix"></div>
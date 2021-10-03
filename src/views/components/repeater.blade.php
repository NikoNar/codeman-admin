<div class="clearfix"></div>
<div class="box">
	<div class="box-body">
		<div class="repeater {{ $class_name }}" id="repeater-{{$name}}">
			<!-- Repeater Heading -->
			<div class="repeater-heading">
			   <div class="col-md-6">
			       <h4>{{ $label }}</h4>
			   </div>
			   <div class="col-md-6">
			       <button type="button" class="btn btn-primary btn-flat pull-right repeater-add-btn">
			           Add Row
			       </button>
			   </div>
			</div>
			<div class="clearfix"></div>
		  	<!-- Repeater Items -->
			
			<!-- Repeater Content -->
		  	@if(isset($repeater_inputs) && !empty($repeater_inputs))
				@if(isset($repeater_values) && !empty($repeater_values))
					@php
						// Getting the keys of $repeater_values
						$rep_arr_keys = array_keys( $repeater_values );
						// Getting the size of the array
						$rep_arr_size = sizeof($repeater_values);
					@endphp
					@for($i = 0; $i < $rep_arr_size; ++$i)
						<div class="items" data-group="{{$name}}">
							<div class="col-md-12">
								<div class="item-content">
									@include('admin-panel::components.additional_options', [
										'additional_options' => $repeater_inputs, 
										'is_repeater' => true,
										'repeater_value' => $repeater_values[$rep_arr_keys[$i]]
									])
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-md-12">
								<!-- Repeater Remove Btn -->
								<div class="pull-right repeater-remove-btn">
								  	<button type="button" id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
								    	Remove
								    </button>
								</div>
							</div>
						</div>
					@endfor
				@else
					<div class="items" data-group="{{$name}}">
						<div class="col-md-12">
							<div class="item-content">
						  		@include('admin-panel::components.additional_options', [
						  			'additional_options' => $repeater_inputs, 
						  			'is_repeater' => true,
						  			'repeater_value' => $repeater_values
						  		])
						  	</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-12">
							<!-- Repeater Remove Btn -->
							<div class="pull-right repeater-remove-btn">
							  	<button type="button" id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
							    	Remove
							    </button>
							</div>
						</div>
					</div>
		  		@endif
	  		@endif
		</div>
	</div>
</div>
<div class="clearfix"></div>
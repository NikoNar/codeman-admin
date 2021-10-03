@if(isset($additional_options) && !empty($additional_options))
	@foreach($additional_options as $key => $val)
		@if(isset($is_repeater))
			@php
				$val['value'] = isset($repeater_value[$val['name']]) ? $repeater_value[$val['name']] : null;
			@endphp
		@endif
		@if(!empty($val['type']))
			@if(isset($is_repeater) || !isset($location) || isset($val['location']) && $val['location'] == $location)
				@if(isset($is_repeater) || !isset($tab) || isset($tab) && isset($val['tab']) && $val['tab'] == $tab)
					@switch($val['type'])
						@case('select')
							@include('admin-panel::components.select', [
								'id'		=>	$key, 
								'label' 	=> 	$val['label'], 
								'name' 		=> 	$val['name'], 
								'type' 		=> 	$val['type'], 
								'options'	=>	is_array($val['type_options']) ? $val['type_options'] : explode(',', $val['type_options']), 
								'is_optgroup' => isset($val['is_optgroup']) ? $val['is_optgroup'] : 0,
								'multiple'	=>	array_key_exists('multiple', $val),
								'info' => isset($val['info']) ? $val['info'] : null,
								'selected' => isset($val['selected']) ? $val['selected'] : "",
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('checkbox')
							@include('admin-panel::components.checkbox', [
								'id'		=>	$key, 
								'label' 	=> 	$val['label'], 
								'name' 		=> 	$val['name'], 
								'type' 		=> 	$val['type'], 
								'options'	=>	is_array($val['type_options']) ? $val['type_options'] : explode(',', $val['type_options']), 
								//'multiple'	=>	array_key_exists('multiple', $val),
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('textarea')
							@include('admin-panel::components.textarea', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name' 	=> 	$val['name'], 
								'value'  => isset($val['value']) ? $val['value'] : null,
								'type' 	=> 	$val['type'],
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('image')
							@include('admin-panel::components.thumbnail', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name' 	=> 	$val['name'], 
								'value'  => isset($val['value']) ? $val['value'] : null,
								'type' 	=> 	$val['type'],
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : '',
								'attributes' => isset($val['attributes']) ? $val['attributes'] : []
							])
						@break 
						@case('gallery')
							@php
								if( isset($val['gallery']) ){
									$images = $val['gallery'];
								}elseif( isset($resource['meta']['gallery']) ){
									$images = $resource['meta']['gallery'];
								}else{
									$images = [];
								}
							@endphp
							@include('admin-panel::components.gallery', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name'  => 	$val['name'], 
								'type'  => 	$val['type'],
								'gallery' => $images,
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('gallery-new')
							@php
								if( isset($val['gallery']) ){
									$images = $val['gallery'];
								}elseif( isset($resource['meta']['gallery']) ){
									$images = $resource['meta']['gallery'];
								}else{
									$images = null;
								}
							@endphp
							@include('admin-panel::components.gallery-new', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name'  => 	$val['name'], 
								'type'  => 	$val['type'],
								'gallery' => $images,
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('editor')
							@include('admin-panel::components.ckeditor', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name' 	=> 	$val['name'], 
								'type' 	=> 	$val['type'],
								'info' => isset($val['info']) ? $val['info'] : null,
								'value'  => isset($val['value']) ? $val['value'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('datetimepicker')
							@include('admin-panel::components.datetimepicker', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name' 	=> 	$val['name'], 
								'value'  => isset($val['value']) ? $val['value'] : null,
								'type' 	=> 	$val['type'],
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('iconpicker')
							@include('admin-panel::components.iconpicker', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name' 	=> 	$val['name'], 
								'type' 	=> 	$val['type'],
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : ''
							])
						@break
						@case('repeater')
							@include('admin-panel::components.repeater', [
								'id'	=>	$key, 
								'label' => 	$val['label'], 
								'name' 	=> 	$val['name'], 
								'type' 	=> 	$val['type'],
								'repeater_inputs' => $val['inputs'],
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : '',
								'repeater_values' => isset($val['value']) ? $val['value'] : null
							])
						@break
						@default
							@include('admin-panel::components.input', [
								'id'	 => $key, 
								'label'  => $val['label'], 
								'name'   => $val['name'], 
								'input_type' => isset($val['input_type']) ? $val['input_type']: 'text',
								'value'  => isset($val['value']) ? $val['value'] : null,
								'info' => isset($val['info']) ? $val['info'] : null,
								'class_name' => isset($val['class_name']) ? $val['class_name'] : '',
								'attributes' => isset($val['attributes']) ? $val['attributes'] : []
							])
						@break
					@endswitch
				@endif
			@endif
		@endif
	@endforeach
@endif
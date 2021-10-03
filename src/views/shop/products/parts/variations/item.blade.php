 
@if(isset($options_count) && !empty($options_count))
	@for($i=1; $i <= $options_count; $i++)
		@include('admin-panel::shop.products.parts.variations.variation')
	@endfor
@elseif(isset($variations) && !empty($variations))
	@foreach($variations as $i => $variation)
		@include('admin-panel::shop.products.parts.variations.variation')
	@endforeach
@endif



@php
	$color = $item->options->where('product_option_group_id', 2)->first();
@endphp
<div class="col-md-3 sort-item ui-state-default" data-id="{!! $item->id !!}" style="margin-bottom: 16px; cursor: grab; padding: 0 8px;">
	@if($item->product->status != 'published')
		<span class="text-uppercase alert-danger" style="position: absolute; top:10px; left: 25px; z-index: 2; padding: 5px 10px">
			{{ $item->product->status }}
		</span>
	@endif
	<div style="padding: 10px; min-height: 280px; background-color: #f5f5f5; position: relative;">
		<img src="{{ image_thumbnail($item->thumbnail, 686, 1028) }}" class="img-fluid img-responsive" style="height: 350px; width: 100%; object-fit: cover;">
		<h4>
			{{ $item->product->title }} 
			<br>
			<small>
				@if(isset($color->value) && is_url($color->value))
					<img src="{{ $color->value }}" alt="{{ $color->name }}" width="12" height="12" style="border-radius: 50%">
				@endif
				{{ isset($color) ? $color->name  : '' }}
			</small>
		</h4>
		<p>{{ $item->price ? $item->price : 'No Price'  }}</p>
	</div>
</div>

<tr data-id="{{ $product->id }}">
	<td class="text-center" style="background-color: #fff">
		<img src="{{$product->image}}"  class="feed_prod_thumb" alt="Product Thumbnail">
	</td>
	<td>
		<a href="{{route('feed-product-show', $product->id )}}" >{{ $product->name }}</a>
	</td>
	<td>
		{{ $product->brand_name }}
	</td>
	<td>
		{{ $product->upc }}
	</td>
	<td>
		{{ $product->merchant_name }}
	</td>
	<td>{{ date('m/d/Y g:i A', strtotime($product->updated_at)) }}</td>
	<td class="action">
		<a href="{{ route('feed-product-show', $product->id ) }}" title="Edit product" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
		<a href="{{ route('feed-product-destroy', $product->id ) }}" title="Delete" class="btn btn-xs delete-product btn-danger"><i class="fa fa-trash"></i></a>
		{{-- @if($product->status == 'active')
			<a href="" title="active"  data-id="{{$product->id}}" class="btn btn-xs btn-success status active"><small>Deactivate</small></a>
		@else
			<a href="" title="active"  data-id="{{$product->id}}" class="btn btn-xs btn-success status non_active"><small>Activate</small></a>
		@endif --}}
		<div class="onoffswitch pull-right">
			@if($product->status == 'active')
		    <input type="checkbox" name="product_status" class="onoffswitch-checkbox" id="product_status-{!! $product->id !!}" value="1" checked >
		    <label class="onoffswitch-label status active" for="product_status-{!! $product->id !!}" data-id="{{ $product->id }}">
		        <span class="onoffswitch-inner"></span>
		        <span class="onoffswitch-switch"></span>
		    </label>
			@else
			<input type="checkbox" name="product_status" class="onoffswitch-checkbox" id="product_status-{!! $product->id !!}" value="1" >
		    <label class="onoffswitch-label status non_active" for="product_status-{!! $product->id !!}" data-id="{{ $product->id }}">
		        <span class="onoffswitch-inner"></span>
		        <span class="onoffswitch-switch"></span>
		    </label>
		    @endif
		</div>
	</td>
</tr>
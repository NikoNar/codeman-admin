<tr data-id="{{ $item->id }}">
	{{-- <td><input type="checkbox" name="checked" value="{{ $item->id }}"></td> --}}
	<td>
		#{{ $item->id }} {{ $item->billing_name }}
	</td>
	<td>
	
		{{!empty($item->payment_method->title) ? $item->payment_method->title : $item->payment_type}}
		{{-- @switch($item->payment_type)
		    @case('cashe_on_delivery')
		        Cash on delivery
		        @break
	        @case('credit_card')
	            Credit Card
	            @break
		    @case('idram')
		        Idram
		        @break
		@endswitch --}}
	</td>
	<td>
		{{ !empty($item->delivery_option->title) ? $item->delivery_option->title : $item->shipping_type}}
	</td>
	<td class="text_capitalize">
		@if(isset($status_label_classes[$item->status]) && isset($statuses[$item->status]))
			<span class="{{ $status_label_classes[$item->status] }}">{{ $statuses[$item->status] }}</span>
		@else
			<span class="label label-warning">{{ $item->status }}</span>
		@endif
	</td>
	<td>{{ date('m/d/Y g:i A', strtotime($item->created_at)) }}</td>
	<td class="text_capitalize">{{ number_format($item->total, 0) }} ₽</td>

	<td class="action">
		<a href="{!! route('admin.orders.show', $item->id) !!}" title="View" class="btn btn-xs btn-primary" >
			<i class="fa fa-eye"></i> Details
		</a>
		@if(isset($item->user))
	  		<a href="{!! route('admin.user.profile', $item->user->id) !!}"  class="btn btn-xs btn-default" title="View Account">
	  			<i class="fa fa-user"></i> Account
	      	</a>
      	@endif
	</td>
</tr>
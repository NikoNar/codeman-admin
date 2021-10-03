{{-- {{dd($order_tracking)}}z --}}
{{-- {{dd(1)}} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')

	<section class="invoice">
		<div class="row">
			<div class="col-md-12">
				<a href="{{ route('admin.orders') }}" class="btn btn-primary btn-flat btn-medium">
					<i class="fa fa-arrow-left"></i> Back to Orders
				</a>
			</div>
		</div>
		<br>
	    <!-- title row -->
	    <div class="row">
	        <div class="col-xs-12">
	          	<h2 class="page-header">
	            	<i class="fa fa-globe"></i> {{ env('APP_NAME') }} - online order #{{ $order->id }}.
	            	<small class="pull-right">
	            		<strong>Date: </strong>{{ date('m/d/Y g:i A', strtotime($order->created_at)) }}
	            		<strong style="margin-left: 20px" class="text_capitalize">
	            			Status:
	            			@if(isset($status_label_classes[$order->status]) && isset($statuses[$order->status]))
	            				<span class="{{ $status_label_classes[$order->status] }}">{{ $statuses[$order->status] }}</span>
	            			@else
	            				<span class="label label-warning">{{ $order->status }}</span>
	            			@endif
	            		</strong>

	            	</small>
        	    	@if($order->status == 'CANCELED_BY_YOU')
        	    		<br>
        				@if(!empty($order->order_status_cancelation->cancellation_reason))
        					<small class="text-right">
        						<strong>Cancelation Reason: </strong>
        						{{$order->order_status_cancelation->cancellation_reason}}
        					</small>
        				@endif
        	    	@endif
	          	</h2>
	        </div><!-- /.col -->
	    </div>
	    <!-- info row -->
	    <div class="row invoice-info">
	        <div class="col-sm-4 invoice-col">
	        	Customer
	        	@if(isset($order->user))
		        	<strong>
		        		(Registed User: {{$order->user->first_name.' '.$order->user->last_name }})
                  		<a href="{!! route('admin.orders.user.login', $order->user->id) !!}"  class="btn btn-sm btn-default" title="Login to Account">
                  			<i class="fa fa-sign-in-alt"></i>
	                  	</a>
		        	</strong>
	        	@else
	        		<strong>(Guest)</strong>
	        	@endif
	        	<address>
		            <strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong><br>
		            Phone: <a href="tel:{!!  $order->billing_phone  !!}">{!!  $order->billing_phone  !!}</a><br>
		            Email: <a href="mailto:{!!  $order->billing_email  !!}">{!!  $order->billing_email  !!}</a>
	          	</address>
	        </div>
	        <!-- /.col -->
	        <div class="col-sm-4 invoice-col">
	        	Ship To:
	        	<address>
		            @if($order->ship_to_another_person)
		            	{{-- <strong>{!! $order->shipping_first_name  !!} {!! $order->shipping_last_name  !!}</strong> <br>
		            	{!!  $order->shipping_address  !!}, {!!  $order->shipping_state  !!}, {!!  $order->shipping_country  !!} <br>
		            	Phone: <a href="tel:{!!  $order->shipping_phone  !!}">{!!  $order->shipping_phone  !!}</a> <br> --}}
		            @else

						@php
							$country = !empty($order->pickuplocation->country)
							? (isset(config('countries-list')[$order->pickuplocation->country]) ? config('countries-list')[$order->pickuplocation->country] : $order->pickuplocation->country)
							: "";
							$city = !empty($order->pickuplocation->city) ? $order->pickuplocation->city  : "";
							$address = !empty($order->pickuplocation->address) ? $order->pickuplocation->address  : "";
							$name = !empty($order->pickuplocation->name) ? $order->pickuplocation->name  : "";
						@endphp

		            	<strong>{!! $order->billing_first_name  !!} {!! $order->billing_last_name  !!}</strong>
		            	<br>Country: <strong>{{
		            		!empty($country) ?
		            		$country  :
		            		(	isset(config('countries-list')[$order->shipping_country])
		            			? config('countries-list')[$order->shipping_country]
		            			: $order->shipping_country
		            		)
		            	}}</strong>
		            	<br>City: <strong>{{ !empty($city) ? $city  : $order->shipping_city  }}</strong>
		            	<br>Street: <strong>{{ !empty($address) ? $address : $order->shipping_address  }}</strong>
		            	<br>Building: <strong>{{ !empty($name) ? $name : $order->shipping_address_building }}</strong>
		            	<br>Appartment/Office: <strong>{{ !empty($name) ? $name : $order->shipping_address_apartment }}</strong>
		            	<br>Phone: <a href="tel:{!!  $order->billing_phone  !!}">{!!  $order->billing_phone  !!}</a>
		            @endif
	          	</address>
	        </div> <!-- /.col -->
            <div class="col-sm-4 invoice-col">
            	Order Note:
            	<p>
            		{!! $order->order_note !!}
            	</p>
            </div> <!-- /.col -->

	    </div> <!-- /.row -->
	    {{-- {{dd($order->items)}} --}}
	    <!-- Table row -->
	    @if(isset($order->items) && !empty($order->items))
		    <div class="row">
		        <div class="col-xs-12 table-responsive">
					<button  type="button" class="btn btn-primary" id="openVariationsModal" data-toggle="modal" data-target="openVariationsModal"
                        data-attache-url="{{ route('order.items.attach-variations', $order->id) }}"
                        data-resource-type="order"
                        data-resource-id ="{{ $order->id }}"
                    >
						Add New Product
					</button>
					{{-- @include('admin-panel::shop.variations.components._modal_variations',['resource_id'=>'order_item']) --}}
					{{-- _modal_variations --}}
		        	<table class="table table-striped">
		          		<thead>
		            		<tr>
		              			<th width="80px">Product</th>
		              			<th class="text-left"></th>
		              			<th width="100px" class="text-left text-right">Regular Price</th>
		              			<th class="text-center" width="150px" >Discount Amount</th>
		              			<th class="text-left">Discount %</th>
		              			<th width="50px" class="text-right">Qty</th>
		              			<th class="text-right">Final Price</th>
		              			<th class="text-right">Action</th>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		@php
		            			$total_regular_price = $total_discount_amount = $finale_price = $total_qty = 0;
		            		@endphp

		            		@foreach($order->items as $item)

		            			@php
		            				$sale_price = 0;
		            				$product_price = $item->price/$item->qty;

		            				if($item->sale_price){
		            					$sale_price = $item->sale_price/$item->qty;
		            					$total_discount_amount += $sale_price - $product_price;
		            				}
		            				$total_regular_price += $product_price;
		            				$finale_price = $total_regular_price - $sale_price;
		            				$total_qty += $item->qty;
		            			@endphp
			            		<tr>
					            	<td>
					            		@if(isset($item->variation) && is_url($item->variation->thumbnail))
					            			@if($item->variation->secondary_thumbnail)
					            				<img src="{!! img_icon_size($item->variation->secondary_thumbnail) !!}" class="thumbnail img-xs ">
					            			@else
					            				<img src="{!! img_icon_size($item->variation->thumbnail) !!}" class="thumbnail img-xs ">
					            			@endif
					            		@else
					            		<img src="{!! img_icon_size($item->product->thumbnail) !!}" class="thumbnail img-xs ">
					            		@endif
					            	</td>
					            	<td>
					            		@if($item->variation->title)
					            			{!! $item->variation->title !!}
					            		@else
					            			{!! $item->title !!}
					            		@endif
					            		@if(isset($item->variation_option_group))
					            			<p>
					            				<small>
					            					<strong>
					            						{{ $item->variation_option_group }}:
					            					</strong>
					            					{{ $item->variation_option_value }}
					            				</small>
					            			</p>
					            		@endif
					            	</td>

					            	<td class="text-right">
					            		{!! number_format($product_price, 0, 0, ' ') !!} ₽
					            	</td>

					            	<td class="text-center">
				            			@if($sale_price > 0)
				            				<span class="text-danger">
				            					{!! number_format($sale_price - $product_price, 0, 0, ' ') !!} ₽
				            				</span>
					            			{{-- <del class="text-muted">
						            			{!! number_format($product_price, 0, 0, ' ') !!} ₽
					            			</del>
					            			<span class="text-danger">
						            			{!! number_format($sale_price, 0, 0, ' ') !!} ₽
					            			</span> --}}
				            			@else
					            			N/A
				            			@endif
					            	</td>
            		            	<td>
            	            			@if($sale_price > 0)
            	            				@php
            	            				    $percent =  100 - ($sale_price/$product_price)*100;
            	            				    $percent = number_format($percent, 0);
            	            				@endphp
            	            				<span class="text-danger">{{ '-'.$percent.'%' }}</span>
            	            			@endif
            		            	</td>
					            	<td class="text-right">{{ $item->qty }}</td>
					            	<td class="text-right">
					            		{{-- {!!  $item->sale_price ? number_format($item->sale_price, 0) : number_format($item->price, 0)  !!} AMD --}}
					            		{!!  $sale_price ? number_format($sale_price, 0, 0, ' ') : number_format($product_price, 0, 0, ' ')  !!} ₽
					            	</td>
									<td class="text-right">
										<form action="{{route('admin.order.item.delete')}}" method="POST">
											@method('DELETE')
											@csrf
											<input type="hidden" name="order_id" value="{{$order->id}}">
											<input type="hidden" name="item_id" value="{{$item->id}}">
											<button type="submit" class="btn btn-danger" >Delete Item</button>
											{{-- <a href="{{route('admin.order.item.delete',[$order->id,$item->id])}}" class="btn btn-danger">Delete Item</a> --}}
										</form>
									</td>
					            </td>
			            		</tr>
		            		@endforeach
		            	</tbody>
		            	<tfoot>
		            		<tr>
		            			<th class="bg-success text-left">Totol:</th>
		            			<th colspan="2" class="bg-success text-right">{{ number_format($total_regular_price, 0, 0, ' ') }} ₽</th>
		            			<th class="bg-success text-danger text-center">{{ number_format($total_discount_amount, 0, 0, ' ') }} ₽</th>
		            			<th colspan="2" class="bg-success text-right">{{ $total_qty }}</th>
		            			<th class="bg-success text-right">{{ number_format($total_regular_price + $total_discount_amount , 0, 0, ' ') }} ₽</th>
		            		</tr>
		            	</tfoot>
		          	</table>
		        </div> <!-- /.col -->
		    </div> <!-- /.row -->
	    @endif

	    <div class="row">
	        <!-- accepted payments column -->
	        <div class="col-xs-6">
	        	<p class="lead">Order Methods</p>
	        	<div class="table-responsive">
	            	<table class="table table-striped">
		              	<tbody>
		              		<tr>
		                		<th style="width:50%">Payment Method:</th>
		                		<td class="text-right">
								{{-- {{dd($item)}} --}}
		                			{{!empty($item->order->payment_method->title) ? $item->order->payment_method->title : $item->payment_type}}
	                				{{-- @switch($order->payment_type)
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
		              		</tr>
		              		<tr>
		                		<th>Shipping Type:</th>
		                		<td class="text-right">
									{{ !empty($item->order->delivery_option->title) ? $item->order->delivery_option->title : $item->shipping_type}}
		                		</td>
		              		</tr>
		            	</tbody>
		            </table>
		        </div>
	        </div>
	        <!-- /.col -->
	        <div class="col-xs-6">
	        	<p class="lead">Order Amount</p>

	        	<div class="table-responsive">
	            	<table class="table table-striped">
		              	<tbody>
		              		<tr>
		                		<th style="width:50%">Subtotal:</th>
		                		<td class="text-right">{!! number_format($order->subtotal, 0, 0, ' ') !!} ₽</td>
		              		</tr>
		              		@if($order->discount_card)
		              			<tr>
		              				<th> Discount Card: </th>
		              				<td class="text-right">
		              					{{ $order->discount_card }}
		              				</td>
		              			</tr>
		              			<tr>
		              				<th>Discount Card Percent: </th>
		              				<td class="text-right">{!!  number_format($order->discount_percent, 0)  !!}%</td>
		              			</tr>
		              			@if(isset($order->discountcard) && null != $order->discountcard)
		              			<tr>
		              				<th>Card Holder: </th>
		              				<td class="text-right">{!!  $order->discountcard->cardholder_name !!}</td>
		              			</tr>
		              			@endif
		              		@endif
		              		<tr>
		                		<th>Shipping:</th>
		                		<td class="text-right">
									@if($order->shipping_price > 0)
										{!!  number_format($order->shipping_price, 0, 0, ' ')  !!} ₽
									@else
										FREE
									@endif
		                		</td>
		              		</tr>
		              		<tr>
		                		<th>Total:</th>
		                		<td class="text-right"> {!! number_format($order->total, 0, 0, ' ') !!} ₽</td>
		              		</tr>
		            	</tbody>
		            </table>
		        </div>
	       </div> <!-- /.col -->
	    </div> <!-- /.row -->
		{{-- //Order Transaction --}}
	    <div class="row">
	        <div class="col-xs-12">
	        	<p class="lead">Order Transaction</p>
	        	<div class="table-responsive">
	            	<table class="table table-striped">
	            		<thead>
	            			<tr>
	            				<th>Merchant</th>
	            				<th>Transaction ID</th>
	            				<th>Amount</th>
	            				<th>Currency</th>
	            				<th>Status</th>
	            				<th>Status Message</th>
	            				{{-- <th>Action</th> --}}
	            			</tr>
	            		</thead>
		              	<tbody>
						@if(isset($order->transactions) && !$order->transactions->isEmpty())
							@php
								$accept_payment_button = true;
							@endphp
							@foreach($order->transactions as $transaction)
								<tr>
									<td class="text_capitalize">{!! $transaction->merchant !!}</td>
									<td>{!! $transaction->transaction_id !!}</th>
									<td>{!! number_format($transaction->amount, 0) !!}</td>
									<td>{!! $transaction->currency !!}</td>
									<td class="text_capitalize">{!! $transaction->status !!}</td>
									<td>{!! $transaction->status_message !!}</td>
									<td>
									@if($accept_payment_button && $transaction->status == 'waiting_for_capture')
											<div class=" col-xs-12 float-right">
												<div class="col-xs-6">
													<a href="{{route('transaction.yoo_kassa.accept_payment',$transaction->transaction_id)}}" class="btn btn-success btn-sm accept_cancel_capture_buttons">Accept Payment</a>
												</div>
												<div class="col-xs-6">
													<a href="{{route('transaction.yoo_kassa.cancel_payment',$transaction->transaction_id)}}" class="btn btn-danger btn-sm accept_cancel_capture_buttons">Cancel Payment</a>
												</div>
											</div>
									@endif
									</td>
								</tr>
							@endforeach
				        @else
							<tr>
								<td colspan="6">
									{{__("No Transactions available for this order")}}
								</td>
							</tr>
				        @endif
		            	</tbody>
		            </table>
		        </div>
	       </div> <!-- /.col -->
	    </div>

		{{-- //order transaction  --}}


		{{-- //order history --}}

	    <div class="row">
	        <div class="col-xs-12">
	        	<p class="lead">Order History</p>
	        	<div class="table-responsive">
	            	<table class="table table-striped" style="width:100%">
	            		<thead>
	            			<tr>
	            				<th width="250px">User</th>
	            				<th width="100px">Type</th>
	            				<th width="500px">Message</th>
	            				<th >Date</th>
	            				{{-- <th>Additional Info</th> --}}
	            			</tr>
	            		</thead>
		              	<tbody>
						@if(isset($order_history) && !$order_history->isEmpty())

							@foreach($order_history as $o_history)

								@php
									$h_type = null;
									if($o_history->historiable_type == "Codeman\Admin\Models\Shop\Order")
									{
										$h_type = "Order";
									}
									elseif($o_history->historiable_type == "Codeman\Admin\Models\Shop\Transaction")
									{
										$h_type = "Tranasaction";
									}
								@endphp

								<tr>
									<td class="text_capitalize">{{!empty($o_history->user->full_name) ? $o_history->user->full_name." (ID {$o_history->user_id})"  : $o_history->order->full_name." (GUEST)" }}</td>

									<td>{{ $h_type }}</td>
									<td>{!! $o_history->message_info !!}</td>

									<td>{{ date('m/d/Y g:i A', strtotime($o_history->created_at)) }}</td>
									{{-- <th class="text_capitalize">
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
											<i class="fas fa-eye"></i>
										</button>
										<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
												@if(!empty($o_history->additional_info))
													@foreach ($o_history->additional_info as $key => $val)
															{{dd($key, $val)}}
													 	<span>{{$key}} - {{$val}}</span><br>
													@endforeach
												@endif
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												</div>
												</div>
											</div>
										</div>
									</th> --}}
								</tr>

							@endforeach

				        @else
							<tr>
								<td colspan="7">
									{{__("History isn't available for this order")}}
								</td>
							</tr>
				        @endif
		            	</tbody>
		            </table>
		        </div>
	       </div>
		   <!-- /.col -->
	    </div>

		{{-- //order history --}}

		{{-- //Order Tracking --}}
			<div class="row">
				<div class="col-xs-12">
					<p class="lead">Order Tracking</p>
						@include('account.parts.tracking',['orders'=>$order_tracking,'hide_parts'=>true])
					</p>
				</div>
			</div>
		{{-- //Order Tracking --}}

		<div class="row">
			<div class="col-6">
				<hr>
				{{ Form::model($order, ['route' => ['admin.order.update.status', $order->id], 'method' => 'POST']) }}
					<div class="col-md-12">
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('status') }}
								<select class="form-control" id="status" name="status">
									@foreach ($statuses as $key => $val )
										<option @if($order->status == $key) selected @endif value="{{$key}}">{{$val}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<div id="order_status_txt_cont">
									@switch($order->status)
										@case('CANCELED_BY_US')
												@include("admin-panel::shop.orders.order_status_template.order_statuses_template",[
														'order'=>$order,
														'order_status_templates'=>$order_status_templates,
														'filter'=>"Отменен нами",
														])
											@break
										@case('CANCELED_BY_YOU')
												{{-- <div>
													@if(!empty($order->order_status_cancelation->cancellation_reason))
														<span>
															<strong>Cancelation Reason: </strong>
															{{$order->order_status_cancelation->cancellation_reason}}
														</span>
													@endif
												</div>
												<div>
													@if(!empty($order->order_status_cancelation->method_to_return))
														<span>
															<strong>Return Method: </strong>
															{{$order->order_status_cancelation->method_to_return}}
														</span>
													@endif
												</div> --}}
											@break
										@case('SHIPPED_PICKUP')
												@include("admin-panel::shop.orders.order_status_template.order_statuses_template",[
														'order'=>$order,
														'order_status_templates'=>$order_status_templates,
														'filter'=>"Отгружен - Самовывоз",
														])
											@break

										@case('PAID')
												<input type='text' id='order_message_input'  class="form-control" name='tracking_number' placeholder='PonyExpress tracking number'
													   value='{{(!empty($order->tracking_number) ?  $order->tracking_number : null)}}' />
											@break

										@case('SHIPPED')
												<input type='text' id='order_message_input'  class="form-control" name='tracking_number' placeholder='PonyExpress tracking number'
													   value='{{(!empty($order->tracking_number) ?  $order->tracking_number : null)}}' />
											@break

										@default
									@endswitch
								</div>
							</div>
							<div class="form-group">
								{{ Form::submit('Update Status', ['class' => 'btn btn-success btn-flat']) }}
							</div>
						</div>
					</div>

				{{ Form::close() }}
			</div>

		</div>
	    <!-- this row will not appear when printing -->
	    </section>
@endsection

@section('script')

	<!-- DataTables -->
	<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>


	<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>
	<script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>

	<script>
		$('.check-transaction-status').on('click', function(e){
			e.preventDefault();

			fetch($(this).attr('href'), {
			  method: 'GET', // or 'PUT'
			  headers: {
			    'Content-Type': 'application/json',
			  },
			  // body: JSON.stringify(data),
			})
			.then(response => response.json())
			.then(data => {
			  // console.log('Success:', data);
			  $('body'). append(data.html);
			  $('body').find('#transaction-details').modal('show');
			})
			.catch((error) => {
			  // console.error('Error:', error);
			  $('body').find('#transaction-details').modal('hide');
			});
		});

		function createOrderStatusInp(inp)
		{
			let order_status_cont = $("#order_status_txt_cont");
			order_status_cont.show();
			if(inp == "textarea")
			{
				order_status_cont.html("<"+inp+">"+"</"+inp+">");
			}else{
				order_status_cont.html("<"+inp+"/>")
			}
		}

		$(document).ready(function(){

			$('.accept_cancel_capture_buttons').on('click', function() {
				$('.accept_cancel_capture_buttons').attr('disabled','disabled');
			});

			if($("#order_status_txt_cont").children().length == 0 )
			{
				$("#order_status_txt_cont").hide();
			}else{
				$("#order_status_txt_cont").show();
			}

			$("body").off("change", 'select#status').on('change', 'select#status', function(){

				let order_id = {!! $order->id !!};
				$(".comment_for_status").html("");
				let status_message =  $("#order_status_txt_cont");
				switch($(this).val()) {
					case "CANCELED_BY_US":
						$.ajax({
							url: '{{ route("admin.get-orders-status.templates") }}',
							type: 'POST',
							data: {
								filter: "CANCELED_BY_US",
								order_id: order_id
							},
							cache:false,
							success: function(data) {
								console.log('data');
								status_message.html(data);
							},
						});
						status_message.show();
						break;
					case "SHIPPED_PICKUP":
					console.log(2);
						$.ajax({
							url: '{{route("admin.get-orders-status.templates")}}',
							type: 'POST',
							data: {
								filter: "SHIPPED_PICKUP",
								order_id : order_id,
							},
							cache:false,
							success: function(data) {
								status_message.html(data);
							},
						});
						status_message.show();
						break;
					case "PAID":
						$("#order_status_txt_cont").html("<input type='text' id='order_message_input' class='form-control' name='tracking_number' value='{{(!empty($order->tracking_number) ?  $order->tracking_number : null)}}' placeholder='PonyExpress tracking number' />")
						$("#order_status_txt_cont").show();
						break;
					default:
						status_message.html("");
						$("#order_status_txt_cont").hide();
				}

			});

			let checked_status_val = null;
			$("body").off("click",".select_status_template_button").on("click",".select_status_template_button",function(){
				$(".comment_for_status").val(checked_status_val);
				$(".close").click();
			});

			$("body").off("click",".template_status_option").on("click",".template_status_option",function(){
				checked_status_val = $('input[name=flexRadioDefault]:checked').val();
			});

			$(".show_history_additional_opt").click(function(){
				$(this).next().slideToggle();
			});

		});

	</script>

@endsection

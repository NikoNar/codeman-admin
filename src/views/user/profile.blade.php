@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
<section class="content">
	<div class="row">
        <div class="col-md-3">
			<!-- Profile Image -->
         	<div class="box box-primary">
            	<div class="box-body box-profile">
              		<img class="profile-user-img img-responsive img-circle" src="https://ui-avatars.com/api/?name={{ $user->full_name }}&color=fff&background=3c8dbc" alt="User profile picture" width="100" height="100">

              		<h3 class="profile-username text-center">{{ $user->full_name }}</h3>

              		<p class="text-muted text-center">DOB:{{ $user->user_birthdate }}</p>

              		<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
						  <b>Orders</b> <a class="pull-right btn btn-xs btn-danger">{{ $user->orders->count('id') }}</a>
						</li>
						<li class="list-group-item">
                            <b>Cart Items</b> <a class="pull-right btn btn-xs btn-danger">{{ $user->cart->sum('qty') }}</a>
						</li>
                        <li class="list-group-item">
                            <b>Wishlist Items</b> <a class="pull-right btn btn-xs btn-danger">{{ $user->wishlist->sum('qty') }}</a>
                        </li>
						<li class="list-group-item">
                            <b>Discount Card</b> <a class="pull-right btn btn-xs btn-danger">{{ $user_discount_cards->count() }}</a>
						</li>
						<li class="list-group-item">
                            <b>User Addresses</b> <a class="pull-right btn btn-xs btn-danger">{{ $user_addresses->count() }}</a>
						</li>
						<li class="list-group-item">
                            <b>Subscribed to Products</b> <a class="pull-right btn btn-xs btn-danger">{{ $variation_subscription }}</a>
						</li>
						<li class="list-group-item">
                            <b>Total Spent</b> <a class="pull-right">{{ number_format($user->orders->sum('total')) }} ₽</a>
						</li>
              		</ul>

{{--					<a href="#" class="btn btn-primary btn-block"><i class="fa fa-sign-in-alt"></i><b> Login to account</b></a>--}}
            	</div>
            	<!-- /.box-body -->
          	</div>
          	<!-- /.box -->
        </div>

        <!-- /.col -->
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><strong>{{ $user->name }}</strong></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#orders" data-toggle="tab" aria-expanded="true">Orders ({{ $user->orders->count('id') }})</a></li>
                            <li><a href="#cart" data-toggle="tab" aria-expanded="false">Cart ({{ $user->cart->sum('qty') }})</a></li>
                            <li><a href="#wishlist" data-toggle="tab" aria-expanded="false">Wishlist ({{ $user->wishlist->sum('qty') }})</a></li>
                            <li><a href="#discount_card" data-toggle="tab" aria-expanded="false">Discount Card ({{ $user_discount_cards->count() }})</a></li>
                            <li><a href="#addresses" data-toggle="tab" aria-expanded="false">User Addresses ({{ $user_addresses->count() }})</a></li>
                            <li><a href="#product_subscription" data-toggle="tab" aria-expanded="false">Product Subscriptions ({{ $variation_subscription }})</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- User Orders Tab -->
                            @include("admin-panel::user.parts.tabs._orders_data_table")

                            <!-- User Product Subscriptions -->
                            @include("admin-panel::user.parts.tabs._product_subscription_data_table")

                            <!-- User cart -->
                            @include("admin-panel::user.parts.tabs._user_cart_data_table")

                            <!-- User wishlist -->
                            @include("admin-panel::user.parts.tabs._user_wishlist_data_table")

                            <!-- User Discount/Loyalty Cards -->
                            @include("admin-panel::user.parts.tabs._discount_cards_data_table")

                            <!-- User Addresses -->
                            @include("admin-panel::user.parts.tabs._user_addresses")
                        </div>
                        <!-- /.tab-content -->
                    </div>

                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@endsection

@section('script')

	<!-- DataTables -->
	<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const currencyFormatter = new Intl.NumberFormat('am-AM', {
                style: 'currency',
                currency: 'RUB',
                minimumFractionDigits: 0
            });

            // User Orders Data Table
            var ordersTable = $('#user-orders').DataTable( {
                order: [ 8, 'desc' ],
                ajax: '{!! route('admin.user.orders', $user->id) !!}',
                columns: [
                    {
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: '<span class="btn btn-xs btn-primary"><i class="fa fa-ellipsis-v"></i></span>',
                        width: '5%'
                    },
                    { data: 'items', render: function(data){
                        let qty = 0;
                        let row = data.map(function(item, j){
                            qty += item.qty;
                        })
                        return qty;
                    } },
                    { data: 'subtotal', render: function(data){
                        return currencyFormatter.format(data);
                    } },
                    { data: 'discount_card', render: function(data){
                        return data ? data : 'N/A';
                    } },
                    { data: 'discount_percent', render: function(data){
                        return data ? data+'%' : 'N/A';
                    }, width: '5%' },
                    { data: 'shipping_price', render: function(data){
                        return data != 0 ? currencyFormatter.format(data) : 'Free';
                    } },
                    { data: 'total', render: function(data){
                        return currencyFormatter.format(data);
                    } },
                    { data: 'status', render: function(data, display, row){
                        return '<span class="'+row.status_label_class+'">'+row.status_label+'</span>';
                    } },
                    { data: 'created_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('hy-AM');
                    }, width: '130px'},
                    { data: 'action', render: function(data, display, row){
                        return '<a href="/admin/orders/show/'+row.id+'" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> Details</a>'
                    } },
                ],
                select: {
                    style:    'os',
                    selector: 'td:not(:first-child)'
                }
            } );
            /* Formatting function for row details - modify as you need */
            function orderProducts ( order ) {
                let html = '<table border="0" style="padding-left:50px;" class="table table-bordered table-striped">\
                    <thead>\
                        <tr>\
                            <th width="80">Image</th>\
                            <th>Product</th>\
                            <th>Qty</th>\
                            <th>Price</th>\
                        </tr>\
                    </thead>\
                    <tboady>';
                order.items.map(function(item, key){
                    html += '<tr>';
                    if (item.variation.secondary_thumbnail) {
                        html += '<td><img src="'+ item.variation.secondary_thumbnail +'" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain;"></td>';
                    }else if (item.variation.thumbnail) {
                        html += '<td><img src="'+ item.variation.thumbnail +'" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain;"></td>';
                    }else{
                        html += '<td><img src="/admin-panel/images/no-image.jpg" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain;"></td>';
                    }
                    if(item.variation_option_group){
                        html += '<td>'+item.title+' <strong>('+item.variation_option_group+': '+item.variation_option_value+')</strong></td>';
                    }else{
                        html += '<td>'+item.title+'</td>';
                    }
                    html += '<td>'+item.qty+'</td>\
                                <td>'+currencyFormatter.format(item.price)+'</td>\
                            </tr>';
                });

                html +='</tboady>\
                </table>';
                return html;
            }
            // Add event listener for opening and closing details for user orders table
            $('#user-orders tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = ordersTable.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( orderProducts(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );
            // End User Orders Data Table

            // User Discount/Loyalty Cards Data Table
            var userDiscountCard = $('#user_discount_cards').DataTable( {
                order: [ 5, 'desc' ],
                ajax: '{!! route('admin.user.discount_card', $user->id) !!}',
                columns: [
                    { data: 'user_id', render: function(data, display, row){
                        return row.user.full_name
                    } },
                    { data: 'discount_card_id', render: function(data, display, row){
                        return row.discount_card.name + ' - ' +row.discount_card.discount_percent +'%';
                    } },
                    { data: 'card_code', render: function(data){
                        return data ? data  : 'N/A';
                    } },
                    { data: 'admin_id', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'status', render: function(data){
                        switch(data) {
                            case 'active':
                                return '<span class="label label-success">Active</span>';
                                break
                            case 'disabled':
                                return '<span class="label label-danger">Disabled</span>';
                            break
                        }
                    } },
                    { data: 'created_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('hy-AM');
                    } },
                    { data: 'id', render: function(data){

                        return `<button type='input' class='delete_user_discount_card btn btn-danger' data-id=${data}>Delete</button>`;
                    } },
                ],
                select: {
                    style:    'os',
                    selector: 'td:not(:first-child)'
                }
            } );

            // User Addresses Data Table
            var countries_name = {!! json_encode(config('countries-list')) !!};
            var userAddresses = $('#user-addresses').DataTable( {
                order: [ 11, 'desc' ],
                ajax: '{!! route('admin.user.user_addresses', $user->id) !!}',
                columns: [
                    { data: 'user_id', render: function(data, display, row){
                        return row.user.full_name;
                    } },
                    { data: 'phone', render: function(data){
                        return data ? data  : 'N/A';
                    } },
                    { data: 'email', render: function(data){
                        return data ? data  : 'N/A';
                    } },
                    { data: 'country', render: function(data){
                        return  countries_name[data] ?  countries_name[data] : 'NULL';
                    } },
                    { data: 'city', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'state', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'address', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'address_apartment', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'address_building', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'type', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'address_note', render: function(data){
                        return data ? data  : 'NULL';
                    } },
                    { data: 'created_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('hy-AM');
                    } },
                ],
                select: {
                    style:    'os',
                    selector: 'td:not(:first-child)'
                }
            } );
            // End User Addresses Data Table

            //User's Product Subscriptions Data Table
            var productSbscription = $('#t_variation_subscription').DataTable( {
                order: [ 4, 'desc' ],
                ajax: '{!! route('admin.variations.subscription.user', $user->id) !!}',
                columns: [
                    { data: 'title', render: function(data,display, row){
                        return row.variation.title;
                    }, width: '200px'},
                    { data: 'secondary_thumbnail', render: function(data,display, row){
                        let html = '';
                        if (row.variation.secondary_thumbnail) {
                            html = '<td><img src="'+ row.variation.secondary_thumbnail +'" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain;"></td>';
                        } else if (row.variation.thumbnail) {
                            html = '<td><img src="'+ row.variation.thumbnail +'" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain;"></td>';
                        }else{
                            html = '<td><img src="/admin-panel/images/no-image.jpg" width="80" height="80" class="thumbnail img-xs"></td>';
                        }
                        return html;
                    }, width: '80px', orderable: false,},
                    { data: 'full_name', render: function(data, display, row){
                        return row.user.full_name;
                    } },
                    {   data: 'created_at', render: function(data){
                            date = new Date(data);
                            return date.toLocaleString('en-US');
                        },
                    },
                    {   data: 'updated_at', render: function(data){
                            date = new Date(data);
                            return date.toLocaleString('en-US');
                        },
                    },
                    { data: 'notified_at', render: function(data){
                        if(data == null){
                            return "N/A";
                        }else{
                            date = new Date(data);
                            return date.toLocaleString('en-US');
                        }
                    } },
                    // {   data: 'action', render: function(data, display, row){
                    //         let html;
                    //         html += '<input type="hidden" value="'+row.id+'" name="created_at">';
                    //         return html;
                    //     },
                    //     width: '130px',
                    //     orderable: true,
                    //     className: 'actions',
                    // }
                ],
                select: {
                    style:    'os',
                    selector: 'td:not(:first-child)'
                }
            } );

            // User Cart Data Table
            var cartTable = $('#user-cart').DataTable( {
                order: [ 1, 'desc' ],
                ajax: '{!! route('admin.user.cart', [$user->id, 'cart']) !!}',
                columns: [
                    {
                        className: 'resouce-table-image',
                        orderable: false,
                        data: 'thumbnail', render: function(data, unknown, row){
                            if(row.variation.secondary_thumbnail && row.variation.secondary_thumbnail != 'NULL'){
                                return '<img src="'+row.variation.secondary_thumbnail+'" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain">';
                            }else if(row.variation.thumbnail != 'NULL'){
                                return '<img src="'+row.variation.thumbnail+'" width="80" height="80" class="thumbnail img-xs"  style="object-fit: contain"';
                            }
                        },
                        defaultContent: '<img src="/admin-panel/images/no-image.jpg" width="80" height="80" class="thumbnail img-xs">',
                        width: '80px'
                    },
                    { data: 'title', render: function(data, unknown, row){
                        return row.variation.title;
                        // if(row.option_group_name){
                        //     return data+' <strong>('+row.option_group_name+': '+row.option_name+')</strong>';
                        // }else{
                        //     return data;
                        // }
                    }, width: '250px' },
                    { data: 'qty', render: function(data){
                        return data;
                    }},
                    { data: 'total_stock', render: function(data, unknown, row){
                        return data;
                        // if(row.variation_id){
                        //     return row.variations_stock_count;
                        // }else{
                        //     return row.product_stock_count;
                        // }
                    } },
                    { data: 'price', render: function(data, unknown, row){
                        return currencyFormatter.format(row.variation.price);

                        // if(row.variation_price > 0){
                        //     return currencyFormatter.format(row.variation_price);
                        // }else{
                        //     return currencyFormatter.format(row.product_price);
                        // }
                    } },
                    { data: 'sale_price', render: function(data, unknown, row){
                        if(row.variation.sale_price){
                            return currencyFormatter.format(row.variation.sale_price);
                        }else{
                            return 'N/A';
                        }
                        // if(row.variations_sale_price > 0){
                        //     let sale_percent = 100 - (row.variations_sale_price/row.variation_price)*100;
                        //     return '<p>'+currencyFormatter.format(row.variations_sale_price)+'</p>\
                        //     <p class="label label-success">SALE: '+sale_percent+'%</p>';
                        // }else if(row.product_sale_price){
                        //     return currencyFormatter.format(row.product_sale_price);
                        // }else{
                        //     return 'N/A'
                        // }
                    } },
                    { data: 'created_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('hy-AM');
                    }, width: '130px'},
                ],
                select: {
                    style:    'os',
                    selector: 'td:not(:first-child)'
                }
            } );

            // User Wishlist Data Table
            var wishlistTable = $('#user-wishlist').DataTable( {
                order: [ 1, 'desc' ],
                ajax: '{!! route('admin.user.cart', [$user->id, 'wishlist']) !!}',
                columns: [
                    {
                        className: 'resouce-table-image',
                        orderable: false,
                        data: 'thumbnail', render: function(data, unknown, row){
                            if(row.variation.secondary_thumbnail && row.variation.secondary_thumbnail != 'NULL'){
                                return '<img src="'+row.variation.secondary_thumbnail+'" width="80" height="80" class="thumbnail img-xs" style="object-fit: contain">';
                            }else if(row.variation.thumbnail != 'NULL'){
                                return '<img src="'+row.variation.thumbnail+'" width="80" height="80" class="thumbnail img-xs"  style="object-fit: contain"';
                            }
                        },
                        defaultContent: '<img src="/admin-panel/images/no-image.jpg" width="80" height="80" class="thumbnail img-xs">',
                        width: '80px'
                    },
                    { data: 'title', render: function(data, unknown, row){
                        return row.variation.product.title;
                        // if(row.option_group_name){
                        //     return data+' <strong>('+row.option_group_name+': '+row.option_name+')</strong>';
                        // }else{
                        //     return data;
                        // }
                    }, width: '250px' },
                    { data: 'total_stock', render: function(data, unknown, row){
                            return data;
                        // if(row.variation_id){
                        //     return row.variations_stock_count;
                        // }else{
                        //     return row.product_stock_count;
                        // }
                    } },
                    { data: 'price', render: function(data, unknown, row){
                        return currencyFormatter.format(row.variation.price);
                        // if(row.variation.price > 0){
                        //     return currencyFormatter.format(row.variation.price);
                        // }else{
                        //     return currencyFormatter.format(row.variation.product.price);
                        // }
                    } },
                    { data: 'sale_price', render: function(data, unknown, row){
                        if(row.variation.sale_price){
                            return currencyFormatter.format(row.variation.sale_price);
                        }else{
                            return 'N/A';
                        }
                        // if(row.variations_sale_price > 0){
                        //     let sale_percent = 100 - (row.variations_sale_price/row.variation_price)*100;
                        //     return '<p>'+currencyFormatter.format(row.variations_sale_price)+'</p>\
                        //     <p class="label label-success">SALE: '+sale_percent+'%</p>';
                        // }else if(row.product_sale_price){
                        //     let sale_percent = 100 - (row.product_sale_price/row.product_price)*100;
                        //     return '<p>'+currencyFormatter.format(row.product_sale_price)+'</p>\
                        //     <p class="label label-success">SALE: '+sale_percent+'%</p>';
                        // }else{
                        //     return 'N/A'
                        // }
                    } },
                    { data: 'created_at', render: function(data){
                        date = new Date(data);
                        return date.toLocaleString('hy-AM');
                    }, width: '130px'},
                ],
                select: {
                    style:    'os',
                    selector: 'td:not(:first-child)'
                }
            } );

            $('body').off('click','.delete_user_discount_card').on('click','.delete_user_discount_card',function(){
                let current_button =  $(this);
                let card_id =  $(this).attr('data-id');
                current_button.closest('tr').remove();
                $.ajax({
                    url: '/admin/users/discount-card/delete/'+card_id,
                    type: 'delete',
                    success: function(result) {
                        toastr.success("Successfully Deleted", "Success");
                        current_button.closest('tbody').remove();
                    }
                });
            });
        })


    </script>
@endsection

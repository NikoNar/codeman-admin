@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
	<style type="text/css">
		.ui-state-highlight { height: 100px; background-color: #f39c12 !important}
		/*table.actions-bar-fixed thead tr th:last-child, 
		table.actions-bar-fixed tbody tr td:last-child
		{
		  	position:sticky;
		  	right:0px;
		  	background-color:#fafafa;
			box-shadow: -1px 6px 3px 2px #eee;
		}*/
	</style>
@endsection
@section('content')
	<div class="box">
	    <div class="box-body">
    		<div class="col-md-1">
	    		<button class="btn btn-primary btn-flat" type="button" data-toggle="collapse" data-target="#big-filter" aria-expanded="false" aria-controls="big-filter">
	    		   <i class="fa fa-filter"></i> Filters
	    		</button>
    		</div>
	    	<div class="col-md-3 no-padding-right">
	    		<div class="col-md-8 no-padding">
					<select name="filter-by-year" id="filter-by-year" class="form-control">
						<option value="">Do Nothing</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div>
	    		<div class="col-md-3 no-padding">
	    			{{-- <a href="javascript:void(0)" class="btn btn-primary btn-flat" id="resource-bulk-action">Apply</a> --}}
	    			<a href="javascript:void(0)" class="btn btn-primary btn-flat">Apply</a>
	    		</div>
	    	</div>
	    	<div class="col-md-2 no-padding-left">
	    	    <div class="form-group">
	    	        <select name="per-page" id="table-perpage" class="form-control w-100"
	    	         data-placeholder="Show Per Page">
	    	            <option value=""></option>
	    	            <option value="10" {{ request()->get('length') == '10' ? 'selected' : null }}>10</option>
	    	            <option value="25" {{ request()->get('length') == '25' ? 'selected' : null }}>25</option>
	    	            <option value="50" {{ request()->get('length') == '50' ? 'selected' : null }}>50</option>
	    	            <option value="100" {{ request()->get('length') == '100' ? 'selected' : null }}>100</option>
	    	            <option value="-1" {{ request()->get('length') == '-1' ? 'selected' : null }}>Show all</option>
	    	        </select>
	    	    </div>
	    	</div>
	    	<div class="col-md-3 no-padding-right">

	    		@include('admin-panel::components.categories', [
	    			'noAddLink' => true,
	    			'noLabel' => true,
	    			'render' => false, 
	    			'selected' => request()->has('relations') &&  isset(request()->get('relations')['categories']) ? request()->get('relations')['categories'] : []
	    		])
	    	</div>
    		<div class="col-md-3">
    			<a href="{{ url("admin/products/create") }}" class="btn btn-primary btn-flat btn-medium pull-right">
    				<i class="fa fa-plus"></i> Create {{ Str::singular(ucwords($module)) }}
    			</a>
    		</div>
    		<div class="clearfix"></div>
    		<hr style="margin:5px">
    		<div class="col-md-12 row">
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

						<div class="col-md-3">
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
    		</div>
    		
    		@include('admin-panel::layouts.data-filters')
    		<div id="resource-container">
    			@include('admin-panel::shop.products.parts.listing')
    		</div>
    		<input type="hidden" name="modelName" id="modelName" value="\Codeman\Admin\Models\Shop\Product">
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')
	<script>
	    $(document).ready(function() {
	    	const dataFiltersForm = $('form#data-filters-form');

	        const currencyFormatter = new Intl.NumberFormat('ru-RU', {
	            style: 'currency',
	            currency: 'rub',
	            minimumFractionDigits: 0
	        });

	        var dataTable = $('#ajax-table').DataTable( {
	        	lengthMenu: [ [ 10, 25, 50, 100, -1], [ 10, 25, 50, 100, "All"] ],
	        	scrollX: true,
	        	scrollY: window.innerHeight - document.getElementById("ajax-table").offsetTop,
	        	processing: true,
	        	serverSide: true,
	        	// stateSave: true,
	        	// "stateSaveCallback": function (settings, data) {
	        	// 	console.log('stateSaveCallback', settings, data);
	        	// },
	        	// fixedColumns:   {
	        	// 	rightColumns: 1
	        	// },
	            order: [2, 'desc' ],
	            ajax: {
	            	url: '{!! url()->current() !!}',
	            	data : function(data){
	            		dataFilters = dataFiltersForm.serializeArray();
	            		data.filters = dataFilters;
	            		
	            		let categories = $('#category').val();
	            		let product_option_groups = $('select[name*="product_option_groups"]');
	            		let option_groups = [];
	            		jQuery.each( product_option_groups, function(key, el){
	            			var filter_name = el.name.replace("[]", "");
	            			option_groups.push({
	            				name : filter_name, value : $(el).val() ? $(el).val() : [],
	            			}); 
	            			// data.relations['option_groups'][name] = $(el).val() ? $(el).val() : null;
	            		})
	            		data.relations = {
	            			'categories' : categories,
	            			'option_groups' : option_groups
	            		}
	            		dataSerialize = function(obj, prefix) {
	            		  var str = [];
	            		  for (var p in obj)
	    		  		  		if (obj.hasOwnProperty(p)) {
			  		  		      var k = prefix ? prefix + "[" + p + "]" : p,
			  		  		        v = obj[p];
			  		  		      str.push((v !== null && typeof v === "object") ?
			  		  		        dataSerialize(v, k) :
			  		  		        encodeURIComponent(k) + "=" + encodeURIComponent(v));
			  		  		    }
	            		  return str.join("&");
	            		}
	            		data.order[0].column = data.columns[data.order[0].column].data
	            		// console.log(data);
	            		let UrlDataObj = data;

	            		delete UrlDataObj['columns'];
	            		let newUrl = dataSerialize(UrlDataObj)
	            		window.history.pushState(newUrl, '', window.location.origin+window.location.pathname+'?'+newUrl);
	            	}
	            },
	            sDom: 'rtip',
	            // colReorder: true,
	            columns: [
	            	
	            	{	data: 'checkbox', render: function(data, display, row){
	            			return '<input type="checkbox" name="checked" value="'+row.id+'">';
	            		},
	            		width: '20px',
	            		orderable: false,
	            	},
	                {   data: 'thumbnail', render: function(data){
		                    if (data) {
		                    	return '<a href="javascript:void(0)" class="featured-img-change media-open">\
		                    		<img src="'+iconSize(data)+'" class="thumbnail img-xs ">\
		                    		<i class="fa fa-camera"></i>\
		                    		<input name="thumbnail" type="hidden" value="">\
		                    	</a>';
	                        }
		                }, 
	                	orderable: false,
	                	defaultContent: '<a href="javascript:void(0)" class="featured-img-change media-open">\
				                    		<img src="/admin-panel/images/no-image.jpg" class="thumbnail img-xs ">\
				                    		<i class="fa fa-camera"></i>\
				                    		<input name="thumbnail" type="hidden" value="">\
				                    	</a>',
				        width:'80px',
	                },
	                { data: 'title', render: function(data){
	                    return data;
	                } },
	                { data: 'categories', render: function(data, row){
	                    let html = '';
	                    data.map(function(item, key){
	                    	html += '<span class="label label-primary">'+item.title+'</span> ';
	                    });
	                    return html;
	                }, sortable : false },
	                { data: 'created_at', render: function(data){
	                    date = new Date(data);
	                    return date.toLocaleString('en-US');
	                }, width: '120px'},
	                { data: 'updated_at', render: function(data){
	                    date = new Date(data);
	                    return date.toLocaleString('en-US');
	                }, width: '120px'},

	                { data: 'status', render: function(data){
	                    switch(data) {
	                        case 'published':
	                            return '<span class="label label-success">'+data+'</span>';
	                            break
	                        case 'archive':
	                            return '<span class="label label-warning">'+data+'</span>';
	                            break
	                        case 'deleted':
	                            return '<span class="label label-danger">'+data+'</span>';
	                            break
	                        default:
	                            return '<span class="label label-default">'+data+'</span>';
	                        break
	                    }
	                } },
	                {	data: 'order', render: function(data, display, row){

                			let html = '<div class="btn-group">\
                            	<a href="{{ url('admin/products') }}/'+row.id+'/edit" class="btn btn-sm btn-info btn-flat" style="width:80%" >Edit {{ Str::singular(ucwords($module)) }}</a>\
                            	<button type="button" class="btn btn-sm btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="width:20%" data-toggle="tooltip" title="Actions">\
                	                <span class="caret"></span>\
                	                <span class="sr-only">Toggle Dropdown</span>\
                              	</button>\
                              	<ul class="dropdown-menu" role="menu">\
                	                <li>\
                            			<a href="{{ url($module) }}/'+row.id+'" target="_blank" title="View"><i class="fa fa-eye"></i> Show</a>\
                	                </li>\
                	                <li>\
                	                	<a href="{{ url('admin/products') }}/'+row.id+'/edit" title="Edit"><i class="fa fa-edit"></i> Edit</a>\
                	                </li>\
                	                <li class="divider"></li>\
                	                <li style="background-color: #dd4b39; margin-top:-10px">\
                	                	<form method="POST" action="{{ url('admin/products') }}/'+row.id+'" accept-charset="UTF-8" enctype="multipart/form-data" class="">\
				                    		<input name="_token" type="hidden" value="{{ csrf_token() }}">\
				                    		<input name="_method" type="hidden" value="DELETE">\
										    <button title="Delete" class="btn btn-link confirm-del w-100" style="color:#fff; text-align: left;padding-left: 20px;"><i class="fa fa-trash" style="margin-right: 10px;"></i> Remove</button>\
										</form>\
                	                </li>\
                              	</ul>\
                            </div>';
                            if(row.variations && row.variations.length > 0){
                            	html += '<button type="button" class="btn btn-sm btn-primary btn-flat collapse-row" data-toggle="tooltip" title="Variations"><i class="fa fa-ellipsis-v"></i></button>';
                            }
                            html += '<span type="button" class="btn btn-sm btn-default btn-flat sortable-handle" data-toggle="tooltip" title="Move"><i class="fa fa-sort"></i></span>\
                            <input type="hidden" value="'+row.id+'" name="order">';
                            return html;
		                }, 
		                width: '160px', 
		                orderable: true,
		                className: 'actions',
	            	},

	                // { data: 'price', render: function(data){
	                //     return currencyFormatter.format(data);
	                // } },
	            ],
	            select: {
	                style:    'os',
	                selector: 'td:not(:first-child)'
	            },
	            createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-id', data.id);
                },
	            drawCallback: function( settings ) {
	            	$('[data-toggle="tooltip"]').tooltip();
	            	dataTableSort();
	            }
	        } );
			
			dataTable.on( 'xhr', function () {
			    var data = dataTable.ajax.params();
			    console.log('Search term was', data)
			    // alert( 'Search term was: '+data.search.value );
			} );

			$('#category, select[name*="product_option_groups"]').on('change', function (e) {
               // dataTable.columns(3).search( $(this).val() ).draw();
               e.preventDefault();
               dataTable.draw();
          	} );

			$('#table-search').on('keyup change', function () {
               dataTable.search( $(this).val() ).draw();
          	} );

			$('#table-perpage').on('change', function () {
               dataTable.page.len( $(this).val() ).draw();
          	} );

          	$('form#data-filters-form').on('submit', function(e){
          		e.preventDefault();
          		dataTable.draw();
          	})

	        // Add event listener for opening and closing details
	        $('#ajax-table tbody').on('click', 'td .collapse-row', function () {
	            var tr = $(this).closest('tr');
	            var row = dataTable.row( tr );
	        
	            if ( row.child.isShown() ) {
	                // This row is already open - close it
	                row.child.hide('slow');
	                tr.removeClass('shown');
	            }
	            else {
	                // Open this row
	                row.child( drowRowChilds(row.data()) ).show('slow');
	                tr.addClass('shown');
	            }
	        } );

	        /* Formatting function for row details - modify as you need */
	        function drowRowChilds ( row ) {
	            let html = '<table border="0" style="padding-left:50px;" class="table table-bordered table-striped">\
	            	<caption class="bg-primary text-white text-uppercase text-center" >'+row.title+'</caption>\
	                <thead>\
	                    <tr>\
	                        <th width="80">Image</th>\
	                        <th>Title</th>\
	                        <th>SKU</th>\
	                        <th>Stock qty</th>\
	                        <th>Price</th>\
	                        <th>Sale Price</th>\
	                        <th>Status</th>\
	                    </tr>\
	                </thead>\
	                <tboady>';
	                    row.variations.map(function(item, key){
	                        html += '<tr>';
	                            if (item.thumbnail) {
	                                html += '<td><img src="'+ iconSize(item.thumbnail) +'" width="80" height="80" class="thumbnail img-xs"></td>';
	                            }else{
	                                html += '<td><img src="/admin-panel/images/no-image.jpg" width="80" height="80" class="thumbnail img-xs"></td>';
	                            }
	                            html += '<td>'+item.title+'</td>';
	                            html += '<td>'+item.sku+'</td>';

	                            // if(item.variation_option_group){
	                            //     html += '<td>'+item.title+' <strong>('+item.variation_option_group+': '+item.variation_option_value+')</strong></td>';
	                            // }else{
	                            //     html += '<td>'+item.title+'</td>';
	                            // }
	                            html += '<td>'+item.stock_count+'</td>';
	                            html += '<td>'+currencyFormatter.format(item.price)+'</td>';
	                            html += '<td>'+currencyFormatter.format(item.sale_price)+'</td>';
	                            html += '<td>'+item.status+'</td>';
	                        html += '</tr>';
	                    });
	                    
	            html +='</tboady>\
	            </table>';
	            return html;
	        }

	        function dataTableSort()
            {
		        $('.table-sortable').sortable({
		        	handle: ".sortable-handle",
	             	items: "tr",
	            	cursor: 'move',
	            	opacity: 0.8,
	            	placeholder: "ui-state-highlight",
	            	update: function(event, ui ) {
	                	renumber_table('.table-sortable');
	              	}
	            });
            }
	    });
	</script>
@endsection
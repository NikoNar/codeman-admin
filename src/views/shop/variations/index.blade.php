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
	    	<div class="col-md-2 no-padding-right">
	    		<div class="col-md-8 no-padding">
					<select name="bulk-action" id="bulk-action" class="form-control">
						<option value="">Do Nothing</option>
						<option value="bulk-edit">Edit</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div>
	    		<div class="col-md-3 no-padding">
	    			<a href="javascript:void(0)" class="btn btn-primary btn-flat" id="apply-bulk-action">Apply</a>
	    		</div>
	    	</div>
	    	<div class="col-md-2 no-padding-right">
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
	    	@if(isset($categories) && !empty($categories))
		    	<div class="col-md-3">
		    		@include('admin-panel::components.categories', [
		    			'noAddLink' => true,
		    			'noLabel' => true,
		    			//'render' => true,
		    			//'selected' => request()->has('relations') ? request()->get('relations')['categories'] : []
		    		])
		    	</div>
	    	@endif
			@if(isset($products) && !empty($products))
	    		<div class="col-md-2 no-padding-right">
    			    <div class="form-group">
    			    	{!! Form::select('product', ['' => 'Select Product' ] + $products , isset($product) ? $product : null, ['class' => 'form-control select2', 'id' => 'table-product']) !!}
    			    </div>
	    		</div>
	    	@endif
    		<div class="col-md-2">
    			<a href="{{ route("variations.create") }}" class="btn btn-primary btn-flat btn-medium pull-right">
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
    		@include('admin-panel::layouts.bulk-edit', [
    			'labels' => $labels
    		])

    		<div class="clearfix"></div>
    		<div id="resource-container">
    			@include('admin-panel::shop.variations.parts.listing')
    		</div>
			<input type="hidden" name="modelName" id="modelName" value="\Codeman\Admin\Models\Shop\Variation" >
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection

@section('script')

	<script>
	    $(document).ready(function() {
	    	$(".select2").select2();
	    	const dataFiltersForm = $('form#data-filters-form');

	        const currencyFormatter = new Intl.NumberFormat('ru-RU', {
	            style: 'currency',
	            currency: 'rub',
	            minimumFractionDigits: 0
	        });

	        var dataTable = $('#ajax-table').DataTable( {
	        	lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
	        	scrollX: true,
	        	// scrollY: window.innerHeight - document.getElementById("ajax-table").offsetTop,
	        	processing: true,
	        	serverSide: true,
	        	// fixedColumns:   {
	        	// 	rightColumns: 1 //this braking dataTableSort function
	        	// },
	            order: [ 9, 'desc' ],
	            ajax: {
	            	url: '{!! url()->current() !!}',
	            	data : function(data){
	            		dataFilters = dataFiltersForm.serializeArray();
	            		data.filters = dataFilters;

	            		let categories = $('#category').val();
	            		let product = $('#table-product').val();
	            		let product_option_groups = $('select[name*="product_option_groups"]');
	            		let option_groups = [];
	            		jQuery.each( product_option_groups, function(key, el){
	            			var filter_name = el.name.replace("][]", "");
	            			filter_name = filter_name.replace("product_option_groups[", "");
	            			option_groups.push({
	            				group_id : filter_name, value : $(el).val() ? $(el).val() : [],
	            			});
	            			// data.relations['option_groups'][name] = $(el).val() ? $(el).val() : null;
	            		})
	            		data.relations = {
	            			'product' : product,
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
	            		let UrlDataObj = data;
	            		delete UrlDataObj['columns'];
	            		let newUrl = dataSerialize(UrlDataObj)
	            		window.history.pushState(newUrl, '', window.location.origin+window.location.pathname+'?'+newUrl);
	            	}
	            },
	            sDom: 'rtip',
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
                    { data: 'title', render: function(data, display, row){
                        return row.product.title;
                    }, width: '180px'},
	                { data: 'properties', render: function(data, display, row){
	                	let html = '';
	                    if(row.options){
	                    	row.options.map(function(item, key){
	                    		html += '<p><b>'+item.product_option_group.name+'</b>: '+item.name+'</p>';
	                    	});
	                    }
	                    return html;
	                }, width: '180px', orderable: false,},
                    { data: 'color_total_stock', render: function(data, display, row){
                            return data
                        }, width: '80px', orderable: false},
	                // { data: 'sku', render: function(data){
	                //     return data;
	                // } },
	                { data: 'product.title', render: function(data){
	                    return data;
	                }, sortable : false, width: '180px' },
	                { data: 'price', render: function(data){
	                    return currencyFormatter.format(data);
	                }, width: '80px' },
	                { data: 'sale_price', render: function(data){
	                    return currencyFormatter.format(data);
	                }, width: '80px' },
	                { data: 'product.categories', render: function(data, display, row){
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
                            	<a href="{{ url('admin/variations') }}/'+row.id+'/edit" class="btn btn-sm btn-info btn-flat" style="width:80%" >Edit {{ Str::singular(ucwords($module)) }}</a>\
                            	<button type="button" class="btn btn-sm btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="width:20%" data-toggle="tooltip" title="Actions">\
                	                <span class="caret"></span>\
                	                <span class="sr-only">Toggle Dropdown</span>\
                              	</button>\
                              	<ul class="dropdown-menu" role="menu">\
                	                <li>\
                            			<a href="{{ url($module) }}/'+row.id+'" target="_blank" title="View"><i class="fa fa-eye"></i> Show</a>\
                	                </li>\
                	                <li>\
                	                	<a href="{{ url('admin/variations') }}/'+row.id+'/edit" title="Edit"><i class="fa fa-edit"></i> Edit</a>\
                	                </li>\
                	                <li class="divider"></li>\
                	                <li style="background-color: #dd4b39; margin-top:-10px">\
                	                	<form method="POST" action="{{ url('admin/variations') }}/'+row.id+'" accept-charset="UTF-8" enctype="multipart/form-data" class="">\
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
		                width: '130px',
		                orderable: true,
		                className: 'actions',
	            	}
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
			    // console.log('Search term was', data)
			    // alert( 'Search term was: '+data.search.value );
			} );

			$('#category, #table-product, select[name*="product_option_groups"]').on('change', function (e) {
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
          	});

            function dataTableSort() {
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

            $('#apply-bulk-action').on('click', function(e){
            	let value = $('select#bulk-action').val();
            	selectedListArr = [];
            	$('input[name="checked"]:checked').each(function(key, el){
            		if(el.value != 'on')
            			selectedListArr.push(el.value)
            	})
            	// console.log(selectedListArr);
            	if(value == 'bulk-edit'){
            		$('#bulk-edit-collapse').collapse("show");
            	}
            })
	    });

		$('#bulk-edit-form').on('submit', function(e){
			e.preventDefault();
			e.stopPropagation();

			ids = [];
			$.each($('input[name="checked"]'), function(k, v){
			    if($(this).prop('checked') && this.value != 'on'){
			        ids.push(this.value);
			    }
			});

			if(ids.length <= 0) return false;

			let form = $(this);
			var formData = new FormData(form[0]);
			formData.append('ids',ids);
			let btn = form.find('button[type="submit"]');
			btn_text = btn.text();
			btn.attr('disabled', 1);
			btn.text('Processing...');
			setTimeout(function(){
				btn.text(btn_text);
				btn.removeAttr('disabled');
				$()
			},1000);

			$.ajax({
		        url: form.attr('action'),
		        type: 'POST',
		        data: formData,
		        processData: false,
		        contentType: false,
		        success: function(result) {
		            dataTable.draw();
		        }
		    });
		});
	</script>
@endsection

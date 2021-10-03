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
					<select name="filter-by-year" id="filter-by-year" class="form-control">
						<option value="">Do Nothing</option>
						<option value="bulk-delete">Delete</option>
					</select>
	    		</div>
	    		<div class="col-md-3 no-padding">
	    			<a href="javascript:void(0)" class="btn btn-primary btn-flat">Apply</a>
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
			
    		{{-- <div class="col-md-2">
    			<a href="{{ route('coupons.create') }}" class="btn btn-primary btn-flat btn-medium pull-right">
    				<i class="fa fa-plus"></i> Create {{ Str::singular(ucwords($module)) }}
    			</a>
    		</div> --}}
    		
    		@include('admin-panel::layouts.data-filters')

    		<div class="clearfix"></div>
    		<div id="resource-container">
    			@include('admin-panel::subscribers.parts.listing')
    		</div>
			<input type="hidden" name="modelName" id="modelName" value="\Codeman\Admin\Models\Subscriber" >
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
	        	lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
	        	scrollX: true,
	        	// scrollY: window.innerHeight - document.getElementById("ajax-table").offsetTop,
	        	processing: true,
	        	serverSide: true,
	        	// fixedColumns:   { 
	        	// 	rightColumns: 1 //this braking dataTableSort function
	        	// },
	            order: [ 1, 'desc' ],
	            ajax: {
	            	url: '{!! url()->current() !!}',
	            	data : function(data){
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
	            		dataFilters = dataFiltersForm.serializeArray();
	            		data.filters = dataFilters;

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
	                {   data: 'email', render: function(data){
		                    return data;
						},
						orderable: true,
	                },
					{   data: 'user', render: function(data, display, row){
							if(row.user_id){
		                    	return row.first_name+' '+row.last_name;
							}
							return 'Not a user';
							
						},
						orderable: false,
	                },
	                { 	data: 'created_at', render: function(data){
							date = new Date(data);
							return date.toLocaleString('en-US');
						},
						width: '120px'
					},
	                { 	data: 'updated_at', render: function(data, display, row){
	                		if(row.active == 0){
								date = new Date(data);
								return date.toLocaleString('en-US');
	                		}
	                		return 'N/A';
						},
						width: '120px'
					},
	                { 	data: 'active', render: function(data){
							switch(data) {
								case 1:
									return '<span class="label label-success">Active</span>';
									break
								case 0:
									return '<span class="label label-danger">Unsubscribed</span>';
									break
							}
						}
					},
	    //             {	data: 'order', render: function(data, display, row){
     //            			let html = '<div class="btn-group">\
     //                        	<a href="{{ url('admin/marketing/coupons') }}/'+row.id+'/edit" class="btn btn-sm btn-info btn-flat" style="width:80%" >Edit {{ Str::singular(ucwords($module)) }}</a>\
     //                        	<button type="button" class="btn btn-sm btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="width:20%" data-toggle="tooltip" title="Actions">\
     //            	                <span class="caret"></span>\
     //            	                <span class="sr-only">Toggle Dropdown</span>\
     //                          	</button>\
     //                          	<ul class="dropdown-menu" role="menu">\
     //            	                <li>\
     //            	                	<a href="{{ url('admin/marketing/coupons') }}/'+row.id+'/edit" title="Edit"><i class="fa fa-edit"></i> Edit</a>\
     //            	                </li>\
     //            	                <li class="divider"></li>\
     //            	                <li style="background-color: #dd4b39; margin-top:-10px">\
     //            	                	<form method="POST" action="{{ url('admin/marketing/coupons') }}/'+row.id+'" accept-charset="UTF-8" enctype="multipart/form-data" class="">\
				 //                    		<input name="_token" type="hidden" value="{{ csrf_token() }}">\
				 //                    		<input name="_method" type="hidden" value="DELETE">\
					// 					    <button title="Delete" class="btn btn-link confirm-del w-100" style="color:#fff; text-align: left;padding-left: 20px;"><i class="fa fa-trash" style="margin-right: 10px;"></i> Remove</button>\
					// 					</form>\
     //            	                </li>\
     //                          	</ul>\
     //                        </div>';
     //                        return html;
		   //              }, 
		   //              width: '130px', 
		   //              orderable: true,
		   //              className: 'actions',
					// }
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

			$('#table-product').on('change', function (e) {
               // dataTable.columns(4).search( $(this).val() ).draw();
               e.preventDefault();
               dataTable.draw();
          	} );

			$('#category').on('change', function (e) {
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
          	});

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
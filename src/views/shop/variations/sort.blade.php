@extends('admin-panel::layouts.app')
@section('style')
	{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.css"> --}}
	{{-- <link href="{!! asset('admin/gridstack-js/custom/gridstack.css') !!}" rel="stylesheet"> --}}
	<style>
		.grid-stack-item-content {
			background: #367fa9;
			color: #fff;
			font-family: 'Indie Flower', cursive;
			text-align: center;
			font-size: 20px;
		}

		.grid-stack-item-content .fa {
		    background: #3c8dbc;
		    width: 30px;
		    height: 30px;
		    text-align: center;
		    line-height: 30px;
		    position: absolute;
		    left: 22px;
		    transform: translate(-50%);
		    border-radius: 50%;
		    font-size: 18px;
		    top: 50px;
		}

		header a,
		header a:hover { color: #fff; }

		.darklue hr.star-light::after { background-color: #2c3e50; }
		.grid-stack-item .ui-resizable-handle{
			color: #fff;
			font-size: 16px;
		}
		.grid-stack-item .remove-item{
			position: absolute;
		    right: 16px;
		    top: 0px;
		    color: #fff;
		    font-size: 16px;
		    cursor: pointer;
		    z-index: 2;
		}
		.img-change  {
		    position: relative;
		    cursor: pointer;
		    /*margin-right: 10px;*/
		    width: 100%;
		    height: 100%;
		    display: inline-block;
		    background-position: center;
		    background-size: cover;
		}
		.grid-stack-item-content .fa{
			font-size: 18px;
			color: #fff;
			line-height: 29px;
		}
		.grid-stack-item .grid-stack-item-content{
			width: 100%;
			border: 1px solid #5e5e5e;
			left: 0;
			right: 0;
			margin: 0; 
			/*padding-top: 8px;*/
			/*padding-right: 8px;*/
			/*background: #ccc;*/
			border-left: 8px solid #fff;
			border-right: 8px solid #fff;
			border-color: #fff;
		}
		.grid-stack>.grid-stack-item>.ui-resizable-sw, .grid-stack>.grid-stack-item>.ui-resizable-se{
			bottom: 10px;
			left: 10px;
		}
		.grid-stack>.grid-stack-item>.grid-stack-item-content{
			overflow-y: hidden;
		}
		.grid-stack>.grid-stack-item>.grid-stack-item-content{width: 100%;left: 0px;right: 0px; }
		.setting-item{
			position: absolute;
			left: 20px;
			color: #fff;
			cursor: pointer;
			font-size: 20px;
			top: 10px;
			z-index: 2;
			background: #3c8dbc;
			width: 30px;
			height: 30px;
			text-align: center;
			line-height: 30px;
			border-radius: 50%;
		}
		.grid-stack-item .remove-item {
		    position: absolute;
		    left: 15px;
		    top: 90px;
		    color: #fff;
		    font-size: 20px;
		    cursor: pointer;
		    z-index: 2;
		    background: #3c8dbc;
		    width: 30px;
		    height: 30px;
		    text-align: center;
		    line-height: 30px;
		    border-radius: 50%;
		}
		.setting-item, .remove-item, .grid-stack-item-content .fa{
			display: none;
		}
		.grid-stack-item:hover .setting-item,
		.grid-stack-item:hover .remove-item,
		.grid-stack-item:hover .grid-stack-item-content .fa
		{
			display: block;
		}
	</style>
@endsection
@section('content')
	<div class="clearfix"></div>
	@include('admin-panel::shop.variations.parts._sort_grid_view')
	<div class="clearfix"></div>	
	<input type="hidden" name="modelName" id="modelName" value="\Codeman\Admin\Models\Shop\Variation" >
@endsection

@section('script')
	
	{{-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> --}}
	{{-- <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script> --}}
	{{-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script> --}}
	{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js"></script> --}}
	{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.js"></script> --}}
	{{-- <script src="{!! asset('admin/gridstack-js/custom/gridstack.js') !!}"></script>	 --}}

	<script>
        function dataTableSort()
        {
	        $('.grid-sortable').sortable({
	        	// handle: ".sortable-handle",
             	items: ".sort-item",
            	cursor: 'move',
            	// opacity: 0.8,
            	// placeholder: "ui-state-highlight",
            	update: function(event, ui ) {
            		console.log(event, ui);
                	renumber_items();
              	}
            });
            $( ".grid-sortable" ).disableSelection();
        }
        //Renumber table rows
        function renumber_items() {
            ids = [];
            $('.grid-sortable .sort-item').each(function() {
                if($(this).data('id') != undefined){
                    ids.push($(this).data('id'));
                }
            });
            updateOrder(ids.reverse());
        }
        dataTableSort();
	</script>
@endsection

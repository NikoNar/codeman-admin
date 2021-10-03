@extends('admin.layouts.app')
@section('style')
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.css">
	{{-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css"> --}}
	{{-- <link href="{!! asset('admin-panel/gridstack-js/custom/gridstack.css') !!}" rel="stylesheet"> --}}
	<style>
		.grid-stack-item-content {
			background: #367fa9;
			color: #fff;
			font-family: 'Indie Flower', cursive;
			text-align: center;
			font-size: 20px;
		}

		.grid-stack-item-content .fa {
			font-size: 64px;
			display: block;
			margin: 20px 0 10px;
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
			font-size: 24px;
			color: #fff;
		}
		.grid-stack-item .grid-stack-item-content{
			width: 100%;
			border: 1px solid #5e5e5e;
			left: 0;
			right: 0;
			margin: 0; 
		}
		.grid-stack>.grid-stack-item>.grid-stack-item-content{width: 100%;left: 0px;right: 0px; }
	</style>
@endsection
@section('content')
	@include('admin.home.parts.slider')
	<div class="clearfix"></div>
	@include('admin.home.parts.grid-view')
	<div class="clearfix"></div>
	@include('admin.home.parts.settings')
	
@endsection

@section('script')
	
	{{-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> --}}
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	{{-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script> --}}
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.js"></script>
	{{-- <script src="{!! asset('admin/gridstack-js/custom/gridstack.js') !!}"></script>	 --}}
	
	{{-- <script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script> --}}
	{{-- <script src="{{ asset('admin-panel/bower_components/ckeditor/adapters/jquery.js') }}"></script> --}}

	
	<!-- DataTables -->
	<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

	<script>

		$(function () {
			var options = {
		        width: 12,
		        // height: 4,
		        animation: true,
		        verticalMargin: 0,
		        resizable: {
	               handles: 'e, se, s, sw, w'
	           	}
		        // removable: true,
		        // float: true,

		    };
		  	var grid = $('.grid-stack').gridstack(options);

		  	// $('body').on('resize', '.grid-stack-item', function(k,v){
		  	// 	// $(this).attr('data-gs-height', 2*$(this).attr('data-gs-width'));
		  	// }, function(){
		  	// 	$(this).attr('data-gs-height', 2*$(this).attr('data-gs-width'));
		  	// })
		  	$('body').off('click', '.grid-stack-item .setting-item').on('click', '.grid-stack-item .setting-item', function(){
		  		var gridItem = $(this).closest('.grid-stack-item');
		  		var form = $('#grid-item-settings-form');
		  		var itemId = gridItem.data('id');
		  		if(parseInt(itemId) !== parseInt(form.find('input[name="item-id"]').val())){
		  			form[0].reset();
		  		}
		  		form.find('input[name="item-id"]').val(itemId);
		  		form.find('input[name="top-text-en"]').val(gridItem.find('input[name="item['+itemId+'][top-text-en]"]').val());
		  		form.find('input[name="bottom-text-en"]').val(gridItem.find('input[name="item['+itemId+'][bottom-text-en]"]').val());
		  		form.find('input[name="url-en"]').val(gridItem.find('input[name="item['+itemId+'][url-en]"]').val());
		  		form.find('input[name="top-text-arm"]').val(gridItem.find('input[name="item['+itemId+'][top-text-arm]"]').val());
		  		form.find('input[name="bottom-text-arm"]').val(gridItem.find('input[name="item['+itemId+'][bottom-text-arm]"]').val());
		  		form.find('input[name="url-arm"]').val(gridItem.find('input[name="item['+itemId+'][url-arm]"]').val());
		  		form.find('select[name="url-action"]').val(gridItem.find('input[name="item['+itemId+'][url-action]"]').val());
		  		$('#drid-item-popup').modal('show');
		  	});
		  	$('body').off('click', '.save-grid-item-settings').on('click', '.save-grid-item-settings', function(e){
		  		e.preventDefault();
		  		var form = $('#grid-item-settings-form');
		  		var itemId = form.find('input[name="item-id"]').val();
		  		var item = $('.grid-stack-item[data-id="'+itemId+'"]');
		  		item.find('input[name="item['+itemId+'][top-text-en]"]').val(form.find('input[name="top-text-en"]').val());
		  		item.find('input[name="item['+itemId+'][bottom-text-en]"]').val(form.find('input[name="bottom-text-en"]').val());
		  		item.find('input[name="item['+itemId+'][url-en]"]').val(form.find('input[name="url-en"]').val());
		  		item.find('input[name="item['+itemId+'][top-text-arm]"]').val(form.find('input[name="top-text-arm"]').val());
		  		item.find('input[name="item['+itemId+'][bottom-text-arm]"]').val(form.find('input[name="bottom-text-arm"]').val());
		  		item.find('input[name="item['+itemId+'][url-arm]"]').val(form.find('input[name="url-arm"]').val());
		  		item.find('input[name="item['+itemId+'][url-action]"]').val(form.find('select[name="url-action"]').val());
		  		$('#drid-item-popup').modal('hide');

		  	});
		  	$('body').off('submit', '#grid-item-settings-form').on('submit', '#grid-item-settings-form', function(e){
		  		e.preventDefault();
		  	});

		  	$('body').off('click', '.grid-stack-item .remove-item').on('click', '.grid-stack-item .remove-item', function(){
		  		$(this).closest('.grid-stack-item').remove();
		  	});

		  	readyForSubmit = false;
		  	$('body').off('submit', '#grid-view-form').on('submit', '#grid-view-form', function(e){
		  		if(!readyForSubmit){
		  			e.preventDefault();
		  		}

		  		$('.grid-stack-item').each(function(){
		  		    var id = $(this).data('id');

		  		    $(this).find('input[name="item['+id+'][x]"]').val($(this).data('gs-x'));
		  		    $(this).find('input[name="item['+id+'][y]"]').val($(this).data('gs-y'));
		  		    $(this).find('input[name="item['+id+'][w]"]').val($(this).data('gs-width'));
		  		    $(this).find('input[name="item['+id+'][h]"]').val($(this).data('gs-height'));
		  		    
		  		});
		  		readyForSubmit = true;
		  		setTimeout( function(){
		  			$('#grid-view-form').unbind('submit').submit();
		  		},1000);
		  	});

		  	$('body').off('click', '.add-stock-item').on('click', '.add-stock-item', function(){
		  		var id = 0;
		  		$('.grid-stack-item').each(function(){
		  		    var val = $(this).data('id');
		  		    if(val > id) id = val;
		  		});
		  		id = id + 1;
		  		var html = '<div class="grid-stack-item" data-id="'+id+'" data-gs-x="0" data-gs-y="0" data-gs-width="1" data-gs-height="1"> <div class="grid-stack-item-content"> <a href="javascript:void(0)" class="img-change media-open"> <i class="fa fa-camera"></i> <input name="item['+id+'][thumbnail]" type="hidden" value="" class="thumbnail"> </a></div> <input name="item['+id+'][x]" type="hidden" value=""> <input name="item['+id+'][y]" type="hidden" value=""> <input name="item['+id+'][w]" type="hidden" value=""> <input name="item['+id+'][h]" type="hidden" value=""> <input name="item['+id+'][top-text-en]" type="hidden" value=""> <input name="item['+id+'][bottom-text-en]" type="hidden" value=""> <input name="item['+id+'][url-en]" type="hidden" value=""> <input name="item['+id+'][top-text-arm]" type="hidden" value=""> <input name="item['+id+'][bottom-text-arm]" type="hidden" value=""> <input name="item['+id+'][url-arm]" type="hidden" value=""> <input name="item['+id+'][url-action]" type="hidden" value=""> <span class="remove-item">x</span> <span class="setting-item"><i class="fa fa-cog"></i></span> </div>';
		  		// html = $(html).gridstack() 
		  		var grid = $('.grid-stack').data('gridstack');
		  		console.log(grid);
		  		var widget = grid.addWidget(html, 0, 0, 1, 1, true)

		  	});


		});
	</script>


	<script>
		var slider_table = $('#slider_table').DataTable({
		  'paging'      : false,
		  'lengthChange': false,
		  'searching'   : false,
		  'ordering'    : false,
		  'info'        : false,
		  'autoWidth'   : true
		});
		$("#slider_table tbody").sortable({
		    helper: fixHelperModified,
		    stop: function(event,ui) {
		        renumber_table('#slider_table')
		    }
		}).disableSelection();

		@if(isset($homeSliders) && !$homeSliders->isEmpty())
			var dCounter = {!! $homeSliders->count() !!}
		@else
	  		var dCounter = 1;
		@endif

  	    $('body').off('click', '#add-slider-row').on('click', '#add-slider-row', function (e) {
  	    	e.preventDefault();
  	        slider_table.row.add([
  	            '<div class="fileupload fileupload-new" data-provides="fileupload border-right"> <div class="fileupload-preview thumbnail" style="width: 100%;"> <img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image"> </div> <div> <span class="btn btn-file btn-primary btn-flat col-md-6 media-open film-director-img"> <span class="fileupload-new"><i class="fa fa-camera"></i></span> </span> <a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail"> <i class="fa fa-trash"></i> </a> </div> <div class="form-group  col-md-12 no-padding"> <label for="thumbnail">Image Url</label> <input type="text" name="slider[thumbnail][]" class="form-control no-padding-right thumbnail" placeholder="Enter image url or chosse from media"  style="width:100%"> </div> </div>',

  	            '<label for="">English</label> <input type="text" name="slider[from_to_date_en][]"  class="form-control no-padding-right" placeholder="From To Date" style="width:100%"> <label for="">Armenian</label> <input type="text" name="slider[from_to_date_arm][]"  class="form-control no-padding-right" placeholder="From To Date" style="width:100%">',
  	            '<label for="">English</label> <textarea name="slider[main_text_en][]" class="form-control ckeditor" placeholder="Text" style="width:100%; height:150px"></textarea> <label for="">Armenian</label> <textarea name="slider[main_text_arm][]" class="form-control ckeditor" placeholder="Text" style="width:100%; height:150px"></textarea>',
  	            '<button class="btn btn-md btn-flat btn-danger remove-slider-row" type="button"><i class="fa fa-minus"> </i></button>'
  	        ] ).draw();
	    	// $('.ckeditor').ckeditor();

  	        // dCounter++;
  	    } );
  	    $('body').off('click', '.remove-slider-row').on('click', '.remove-slider-row', function (e) {
  	    	e.preventDefault();
  	    	// if(dCounter > 1){
  	    		slider_table.row( $(this).closest('tr') ).remove().draw();
  	    		// dCounter--;
  	    	// }
  	    });

	</script>
@endsection

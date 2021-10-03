@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<style>
		.draggables, .dragged {
			border: 1px solid #eee;
			width: 100%;
			min-height: 20px;
			list-style-type: none;
			margin: 0;
			padding: 5px 0 0 0;
			float: left;
			margin-right: 10px;
		}
		.draggables li, .dragged li {
			margin: 0 5px 5px 5px;
			/*padding: 5px;*/
			font-size: 1.2em;
			width: 100%;
		}
	</style>
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')


<div class="col-md-8  col-md-offset-2 no-padding-left ">
	<div class="box">
	    <div class="box-header with-border" style="padding-bottom:0">
			<div class="col-md-12">
				@if(!isset($resource))
					<h3 class="box-title">Create {{Str::singular(ucwords($module))}}</h3>
				@else
					<input type="hidden"  id="resource_id" value="{{$resource->id}}">
					<h3 class="box-title">Edit {{Str::singular(ucwords($module))}}</h3>
					<a href='{{ url("admin/marketing/$module/create") }}' class="btn btn-primary btn-flat pull-right ">Add New</a>
				@endif
			</div>
			<div class="clearfix"></div>
			<br>
			<div class="col-md-12">
				<ul class="nav nav-tabs nav-justified pull-right">
					<li role="presentation" class="nav-item active"><a href="#general" aria-controls="General" role="tab" data-toggle="tab">General</a></li>
					<li role="presentation" class="nav-item"><a href="#restrictions" aria-controls="Restrictions" role="tab" data-toggle="tab">Restrictions</a></li>
					<li role="presentation" class="nav-item"><a href="#limitations" aria-controls="Limitations" role="tab" data-toggle="tab">Limitations</a></li>
				</ul>
			</div>
	    </div>
	    <div class="box-body">
	        @include('admin-panel::shop.coupons.parts.forms._create_edit_form')
	    </div>
	    <!-- /.box-body -->
	</div>
</div>
<div class="clearfix"></div>
@endsection
@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

	<script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
	
	{!! JsValidator::formRequest('\Codeman\Admin\Http\Requests\Shop\CouponRequest') !!}
	<script>
        $('select').select2();
	  	$(function () {
	  		if($('.editor').length > 0){
	  			$.each($('.editor'), function(el){
	  				console.log(el)
					CKEDITOR.replaceClass(el);
	  			});
			}
	  	})
  	
	  	
	  </script>
	<script src="{{ asset('admin-panel/plugins/sortable/Sortable.min.js') }} "></script>

	<script>
		$( function() {
			$( ".draggables, .dragged" ).sortable({
				connectWith: ".connectedSortable"
			}).disableSelection();
		} );


		$('body').off('submit', 'form').on('submit', 'form', function (e) {
			if($(this).attr('id') != 'store_category') {
				e.preventDefault();
				var relations = {};
				// $('.dragged').each(function () {
				//     var name = $(this).data('name');
				//     var ids = $(this).find('li').map(function() {
				//         return $(this).data("id");
				//     }).get().join();
				//     relations[name] = ids;
				// });
				var ids = $('.dragged').find('li').map(function () {
					relations[$(this).data('id')] = {'resourceable_type': $(this).closest('ul').data('name')};
				});


				$('#relations').val(JSON.stringify(relations));
				$(this)[0].submit();
			}
		});

		$('.gallery-show-container').each(function(){
			var gallery_id = $(this).data('meta');
			if (window.hasOwnProperty('galleryObj')){
				var currentGallery = galleryObj[gallery_id];
			}
			var sortable = Sortable.create(this, {
				// Element dragging ended
				onEnd: function (evt) {
					var itemEl = evt.item;  // dragged HTMLElement
					evt.to;    // target list
					evt.from;  // previous list
					evt.oldIndex;  // element’s old index within old parent
					evt.newIndex;  // element’s new index within new parent
					old_index = evt.oldIndex - 1;
					new_index = evt.newIndex - 1;

					function arrayMove(array, old_index, new_index) {
						if (new_index >= array.length) {
							var k = new_index - array.length;
							while ((k--) + 1) {
								array.push(undefined);
							}
						}
						array.splice(new_index, 0, array.splice(old_index, 1)[0]);
						return array; // for testing purposes
					};
					// console.log(old_index, new_index);
					if(currentGallery){
						currentGallery = arrayMove(currentGallery, old_index, new_index);
					} else {
						currentGallery = arrayMove(galleryImagesArr, old_index, new_index);
					}

					if (gallery_id) {
						$('.gallery-container[data-id='+gallery_id+']').find('.meta_images').val(JSON.stringify(currentGallery));
					} else {
						$('#images').val(JSON.stringify(currentGallery));
					}
				},
			});
		});
	</script>
	<script>
		function myFunction() {
			// Declare variables
			var input, filter, ul, li, a, i, txtValue;
			input = document.getElementById('myInput');
			filter = input.value.toUpperCase();
			ul = document.getElementById("myUL");
			li = ul.getElementsByTagName('li');

			// Loop through all list items, and hide those who don't match the search query
			for (i = 0; i < li.length; i++) {
				a = li[i].getElementsByTagName("a")[0];
				txtValue = a.textContent || a.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					li[i].style.display = "";
				} else {
					li[i].style.display = "none";
				}
			}
		}
	</script>
	  <!-- <script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script> -->
	
	{{-- Product Options Groups(Attributes) Tab --}}
	<script>
	 	$('#attributes').off('click','#option_groups_add_btn').on('click', '#option_groups_add_btn', function (e) {
	 		let selected_group_id = $('#option_groups_select').val();

	 		$.ajax({
	 		    type: 'GET',
	 		    url: app.ajax_url + '/admin/product-options/'+selected_group_id+'/options?single_choice=true',
	 		    dataType: 'JSON',
	 		    success: function(data){
	 		    	console.log(data);
	 		        if(data.status == 'success'){
	 		        	$('#attributes').find('.options-container').append(data.html);
	 		        	$('#panel-'+selected_group_id).find('.select2').select2();
	 		        }
	 		    }
	 		});
	 	});

	 	$('body').off('click', '.delete-box-group').on('click', '.delete-box-group', function(){
	 		let $this = $(this);
	 		$this.attr('disabled', true);
	 		$this.find('i').addClass('fa-cog fa-spin');
	 		$this.find('i').removeClass('fa-trash');
	 		if($(this).hasClass('remove-variation')){
	 			let id = $(this).data('id');
	 			if(id != undefined){
	 			    $.ajax({
	 			        type: 'GET',
	 			        url: app.ajax_url + '/admin/variations/delete/'+id,
	 			        dataType: 'JSON',
	 			        success: function(data){
	 			            console.log(data);
	 			            if(data.status == 'success'){
	 			                $this.closest('.box-group').fadeOut('slow', function(){
                		 			$(this).remove();
                		 		});
	 			            }
	 			        }
	 			    });
	 			}
	 		}else{
		 		$this.closest('.box-group').fadeOut('slow', function(){
		 			$(this).remove();
		 		});
	 		}	

		 });
		 
		$('#generate-coupon-code').on('click', function(e){
			e.preventDefault();
			let input = $('input[name="code"]');
			$.ajax({
	 		    type: 'GET',
	 		    url: app.ajax_url + '/admin/marketing/coupons/generate-code',
	 		    dataType: 'JSON',
	 		    success: function(data){
	 		        if(data.status == 'success'){
						input.val(data.code);
	 		        }
	 		    }
	 		});
		})
	</script>
@endsection()
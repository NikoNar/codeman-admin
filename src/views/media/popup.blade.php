<div id="media-popup" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Choose Image</h4>
			</div>
			<div class="modal-body">
				<div class="">
				    <div class="box-body">
				    	<div class="col-md-12 no-padding">
				    		<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left upload-image-dropzone">Upload Image</a>
				    		<div class="pull-left col-md-3">
				    			@if(isset($dates) && !empty($dates))
									<select name="filter-by-year" id="filter-by-year" class="form-control pull-left" style="width:100%">
										<option value="">All Dates</option>
										@foreach( $dates as $date)
											<option value="{{$date->month}},{{$date->year}}" @if(isset($selected_year) && $selected_year == $date->month.','.$date->year) selected @endif> {!! date('F', mktime(0, 0, 0, $date->month, 10)) !!} {{$date->year}}</option>
										@endforeach
									</select>
								@endif
				    		</div>
				    		<div class="pull-right col-md-3">
				    			@if(isset($dates) && !empty($dates))
				    				<div class="input-group">
										<input type="text" name="media-search" id="media-search" placeholder="Search By Name" class="form-control">
										<div class="input-group-addon">
							        		<i class="fa fa-search"></i>
							        	</div>
				    				</div>
								@endif
				    		</div>
				    	</div>
				    	<div class="clearfix"></div>
				    	<br>
				    	<div class="upload_section" style="display: none;">
				    		<hr class="no-margin-top">
							@include('admin-panel::media.parts.forms._upload_images_form')
				    	</div>
				    	<hr class="no-margin-top">
				    	<div class="infinite-scroll media-container" style="overflow-y: scroll;height: 500px;">
					    		<div class="media-container-items">
						    		@if(isset($images) && !$images->isEmpty())
						    			@foreach($images as $key => $image)
						    				{{-- {!! dd($image) !!} --}}
											<div class="item {!! isset($multichoose) && $multichoose == 1 ? 'multiple' : null !!}" data-id={!! $image->filename !!} data-index="{!! $key !!}">
												{{-- <button type="button" class="close delete-file" aria-label="Close">
													<i class="fa fa-trash delete-file-icon"></i>
												</button> --}}
												@switch($image->file_type)
													@case('application/pdf')
													<img src="{!! url('admin-panel/images/icons/extentions/pdf.png')!!}"  alt="" class="img-responsive icon" style="background-color: #fff">
													<input type="hidden" name="source" value="{!! url('/media/otherfiles/'.$image->filename) !!}">
													@break

													@case('application/msword')
													<img src="{!! url('admin-panel/images/icons/extentions/doc.png')!!}"  alt="" class="img-responsive icon" style="background-color: #fff">
													<input type="hidden" name="source" value="{!! url('/media/otherfiles/'.$image->filename) !!}">
													@break

													@case('application/vnd.ms-powerpoint')
													<img src="{!! url('admin-panel/images/icons/extentions/ppt.png')!!}"  alt="" class="img-responsive icon" style="background-color: #fff">
													<input type="hidden" name="source" value="{!! url('/media/otherfiles/'.$image->filename) !!}">
													@break

													@case('application/vnd.openxmlformats-officedocument.wordprocessingml.document')
													<img src="{!! url('admin-panel/images/icons/extentions/doc.png')!!}"  alt="" class="img-responsive icon" style="background-color: #fff">
													<input type="hidden" name="source" value="{!! url('/media/otherfiles/'.$image->filename) !!}">
													@break

                                                    @case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
													<img src="{!! url('admin-panel/images/icons/extentions/xls.png')!!}"  alt="" class="img-responsive icon" style="background-color: #fff">
													<input type="hidden" name="source" value="{!! url('/media/otherfiles/'.$image->filename) !!}">
													@break

													@default
													@if(!is_url($image->filename))
														<img src="{!! url('/media/icon_size').'/'.$image->filename !!}" alt="" class="thumbnail img-responsive">
													@else
														<img src="{!! $image->filename !!}" alt="" class="thumbnail img-responsive">
													@endif
												@endswitch
												<span class="filename">
													{!! $image->original_name !!}
												</span>
												<input type="hidden" name="filename" value="{!! $image->original_name !!}">
												<input type="hidden" name="alt" value="{!! $image->alt !!}">
												<input type="hidden" name="width" value="{!! $image->width !!}">
												<input type="hidden" name="height" value="{!! $image->height !!}">
												<input type="hidden" name="file_size" value="{!! $image->file_size !!}">
												<input type="hidden" name="file_type" value="{!! $image->file_type !!}">
												@if(!is_url($image->filename))
													@if(isset($pdf) && $pdf == 'pdf' )
														<input type="hidden" name="full-size-url" value="{!! url('/media/otherfiles').'/'.$image->filename !!}">
													@else
														<input type="hidden" name="full-size-url" value="{!! url('/media/full_size').'/'.$image->filename !!}">
													@endif

												@else
													<input type="hidden" name="full-size-url" value="{!! $image->filename !!}">
												@endif

												<input type="hidden" name="created_at" value="{!! date('F d, Y @ H:i', strtotime($image->created_at)) !!}">
											</div>
										@endforeach
										@if(isset($multichoose) && $multichoose == 1)
											{!! $images->appends('json', false)->appends('multichoose', 'true')->links() !!}
										@else
											{!! $images->appends('json', false)->links() !!}
										@endif
									@endif
					    		</div>
				    	</div>
				    </div>
				</div>
				{!! Form::hidden('csrf-token', csrf_token(), ['id' => 'csrf-token']) !!}
			</div>
			<div class="modal-footer">
				@if(isset($ckeditor) && $ckeditor == true )
					<button type="button" class="btn btn-primary use_in_editor">Use Selected Files</button>
				@elseif( isset($multichoose) && $multichoose == 1 )
					<button type="button" class="btn btn-success multiple-select" data-meta="{{isset($meta)? $meta : 0}}">Use Selected Images</button>
				@endif
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>

	<script>
		$('.infinite-scroll ul.pagination').hide();
	    $(function() {
	        $('.infinite-scroll').jscroll({
	            // autoTrigger: true,
	            loadingHtml: '<i class="fa fa-spinner faa-spin animated"></i>',
	            padding: 100,
	            nextSelector: '.pagination li.active + li a',
	            contentSelector: 'div.media-container-items',
	            callback: function() {
	                $('.infinite-scroll ul.pagination').remove();
	                // console.log('aa')
	            }
	        });
	    });
		$('select').select2();
		$('body').off('click', '.media-container .item:not(.multiple)').on('click', '.media-container .item:not(.multiple)', function(e){
			e.preventDefault();

			if((typeof resource_id != 'undefined' || typeof resource_id != undefined ) && resource_id != 0){
				var img_url = $(this).find('img').attr('src');
				
				$.ajax({
				    type: 'POST',
				    url: app.ajax_url + '/admin/change-resource-featured-image',
				    dataType: 'JSON',
				    data: {
				    	'id' : resource_id,
				    	'model': $('#modelName').val(),
				    	'thumbnail' : $(this).find('input[name="full-size-url"]').val(),
				    	'_token' : $('#csrf-token').val()
				    },
				    success: function(data){
				        if(data.status == 'success'){
				        	$('body').find('tr[data-id="'+resource_id+'"]').find('.featured-img-change img').attr('src', img_url);
				        }
				    }
				});
				
			}else if((typeof chnage_just_image != 'undefined' || typeof chnage_just_image != undefined ) && chnage_just_image != 0){
				// console.log(chnage_just_image);
				chnage_just_image.css('background-image', "url('"+$(this).find('input[name="full-size-url"]').val()+"')");
				chnage_just_image.find('input[name="thumbnail"]').val($(this).find('input[name="full-size-url"]').val());
				chnage_just_image.find('input.thumbnail').val($(this).find('input[name="full-size-url"]').val());
				
			}else if((typeof film_director_img_tr != 'undefined' || typeof film_director_img_tr != undefined ) && film_director_img_tr != 0){
				
				film_director_img_tr.find('.fileupload-preview').find('img').attr('src', $(this).find('img').attr('src'));
				// film_director_img_tr.find('input[name="directors[thumbnail][]"]').val($(this).find('input[name="full-size-url"]').val());
				film_director_img_tr.find('input[name="colors[pic][]"]').val($(this).find('input[name="full-size-url"]').val());
				film_director_img_tr.find('input[name="colors[prod][]"]').val($(this).find('input[name="full-size-url"]').val());

				film_director_img_tr.find('input.thumbnail').val($(this).find('input[name="full-size-url"]').val());
			}else{

				thumbnail_container.find('.fileupload-preview').find('img').attr('src', $(this).find('img').attr('src'));
				thumbnail_container.find('input#thumbnail').val($(this).find('input[name="full-size-url"]').val());
				thumbnail_container.find('input.thumbnail').val($(this).find('input[name="full-size-url"]').val());
			}
			$('#media-popup').modal('hide');
			setTimeout(function(){
				$('#media-popup').remove();
			},1000);
		});
		// console.log(typeof galleryImagesArr );
		if (typeof galleryImagesArr == 'undefined') {
		    var	galleryImagesArr = [];
		}
		// if (typeof imagesArr == 'undefined') {
		    var	imagesArr = [];
		// }
		// if (typeof imagesUrlsArr == 'undefined') {
		    var	imagesUrlsArr = [];
		// }
		$('body').off('click', '.media-container .item.multiple').on('click', '.media-container .item.multiple', function(e){
			e.preventDefault();

			// console.log($(this).has('.img-selected').length);
			var index = $(this).data('index');
			if($(this).has('.img-selected').length == 0){
				$(this).prepend('<span class="img-selected"><i class="fa fa-check selected-icon"></i></span>');
				if (imagesArr[index] == undefined) {
				    imagesArr[index] = {
				    	'url': $(this).find('input[name="full-size-url"]').val(),
				    	'alt': $(this).find('input[name="alt"]').val()
				    };
				    imagesUrlsArr.push({
				    	'url': $(this).find('img').attr('src'),
				    	'alt': $(this).find('input[name="alt"]').val()
				    });
				}
			}else{
				$(this).find('.img-selected').remove();
				// var index = imagesArr.indexOf($(this).data('index'));
				// console.log($(this).data('index'));
				console.log(index);
				var indexOfUrl = imagesUrlsArr.indexOf($(this).find('img').attr('src'));
				if (index > -1) {
				    imagesArr.splice(index, 1);
				    // imagesArr[index] = undefined;
				    imagesUrlsArr.splice(indexOfUrl, 1);
				}
			}
			// console.log(imagesArr);
			// console.log(imagesUrlsArr);
		});
		$('body').off('click', '.multiple-select').on('click', '.multiple-select', function(e){

			e.preventDefault();
			e.stopPropagation();
			// console.log(imagesArr);
			// var imageAlt
			 var feed = ($(this).hasClass('feed'))? true : false;
			



			if(imagesUrlsArr.length > 0)
			{
				if($(this).data('meta') > 0 ){
					var container =	$(".gallery-show-container[data-meta='" + $(this).data('meta') + "']");
				} else {
					console.log(container);
					var container = $(".gallery-show-container");
				}
				if(feed){
					$('.empty-gallery').fadeOut();
					for (var i = imagesUrlsArr.length - 1; i >= 0; i--) {
						console.log(imagesUrlsArr[i]);
						if(imagesUrlsArr[i] != undefined){
							container.append('<div class="media-item"><i class="fa fa-times-circle remove"></i><i class="fa fa-arrows-alt gallery-image-sort"></i><img src="'+imagesUrlsArr[i].url+'" class="thumbnail"></div>');
							galleryImagesArr.push(imagesUrlsArr[i]);
						}
					}
					$('#images').val(JSON.stringify(galleryImagesArr));
					console.log(galleryImagesArr);
					// console.log(galleryImagesArr.length);
					$('#media-popup').modal('hide');
					setTimeout(function(){
						$('#media-popup').remove();
					},1000);

				} else {

				container.find('.empty-gallery').fadeOut();
				// var container = $('.gallery-show-container');
				for (var i = imagesUrlsArr.length - 1; i >= 0; i--) {
					console.log(imagesUrlsArr[i]);
					if(imagesUrlsArr[i] != undefined){
						container.append('<div class="media-item"><i class="fa fa-times-circle remove"></i><i class="fa fa-arrows-alt gallery-image-sort"></i><img src="'+imagesUrlsArr[i].url+'" class="thumbnail"><input name="thumbnail-alt" class="form-control" value="'+imagesUrlsArr[i].alt+'" placeholder="Alt Name"></div>');
						galleryImagesArr.push(imagesUrlsArr[i]);
					}
				}
					if($(this).data('meta') > 0 ){
						$(".multichoose[data-meta='" + $(this).data('meta') + "']").find('.meta_images').val('');
						$(".multichoose[data-meta='" + $(this).data('meta') + "']").find('.meta_images').val(JSON.stringify(galleryImagesArr));
					}else{
						$('#images').val(JSON.stringify(galleryImagesArr));
					}
				console.log(galleryImagesArr);
				// console.log(galleryImagesArr.length);
				$('#media-popup').modal('hide');
				setTimeout(function(){
					$('#media-popup').remove();
				},1000);

				}
				
			}

		});

	</script>

	<script src="{{ asset('admin-panel/plugins/dropzone/dropzone.js') }}"></script>

	<script src="{{ asset('admin-panel/js/dropzone-helper.js') }}"></script>
</div>






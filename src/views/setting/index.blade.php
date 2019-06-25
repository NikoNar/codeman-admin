@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
	<div class="col-md-8 col-md-offset-2">
		<h3>Settings</h3>
		<div class="box">
		    <div class="box-body">
	    		<div class="clearfix"></div>
	    		<br>

	    		<div id="resource-container">
					{!! Form::model($settings, ['route' => 'setting.update', 'method' => 'POST', 'files' => true]) !!}
					<div class="col-md-12">
						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">	
								{!! Form::label('site_name', 'Site Name:') !!}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-font"></span>
								    </span>
									{!! Form::text('site_name', null, ['class' => 'form-control', 'placeholder' => env('APP_NAME')]) !!}
								</div>
							</div>
						</div>
						

						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">	
							{{ Form::label('index', 'Home Page:') }}
						</div>
						<div class="col-md-9">
							<div class="form-group">	
								<div class="input-group">
								    <span class="input-group-addon">
								        <span class="fa fa-user"></span>
								    </span>
									{{ Form::select('index', $pages, isset($selected) && $selected != null ? $selected : null) }}
								</div>
					    	</div>
				    	</div>


						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
							{{ Form::label('all_langs', 'Supported Languages:') }}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class="input-group">
								    <span class="input-group-addon">
								        <span class="fa fa-user"></span>
								    </span>
									@include('admin-panel::layouts.parts._all_languages')
								</div>
							</div>
						</div>

						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
							{{ Form::label('default_lang', 'Default Language:') }}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class="input-group">
								    <span class="input-group-addon">
								        <span class="fa fa-user"></span>
								    </span>
									{{ Form::select('default_lang', $languages, isset($selected) && $selected != null ? $selected : null) }}
								</div>
							</div>
						</div>

					</div>
					
					<div class="col-md-12">
						<h4>Social Icons</h4>
						<hr>
						<div class="social-icons-group">
							@if(isset($settings) && isset($settings['social']))
								@foreach($settings['social'] as $key => $value)
									<div class="item">
										<div class="col-md-4">
											<div class="form-group">
												<div class='input-group'>
												    <span class="input-group-addon">
												        <span class="fa {{ $value->name }}"></span>
												    </span>
												    <select name="social[{{$key}}][name]" class="social_icon_name">
														@include('admin-panel::layouts.parts._fontawesom_dropdown',['selected' => $value->name])
												    </select>
												</div>
											</div>
										</div>
										<div class="col-md-7">
											<div class="form-group">
												<div class='input-group'>
												    <span class="input-group-addon">
												        <span class="fa fa-link"></span>
												    </span>
													{!! Form::text('social['.$key.'][url]', $value->url ?? null,  ['class' => 'form-control social_icon_url', 'placeholder' => 'Socioal Site Url', 'required']) !!}
												</div>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<span class="fa fa-minus btn btn-danger btn-flat remove-row"></span>
											</div>
										</div>
									</div>

								@endforeach
							@endif
						</div>
						<a class="btn btn-success btn-flat pull-right add-social-row"> Add New Social Icon</a>
						
						<div class="clearfix"></div>
					</div>
					<!-- <div class="col-md-12">
						<h4>Links</h4>
						<hr>
						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
							{!! Form::label('submit_film_link', 'Submit a Film Link:') !!}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-link"></span>
								    </span>
									{!! Form::text('submit_film_link', null, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-12">
						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
							{!! Form::label('download_program', 'Downloadble Program:') !!}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group download_file_container'>
									{!! Form::file('download_program', null, ['class' => 'form-control']) !!}
									@if(isset($settings['download_program']) && !empty($settings['download_program']))
										{!! Form::hidden('download_program_file_name', $settings['download_program'], ['class' => 'file_name']) !!}
										<i>Current File: <span class="file_name_text">{!! $settings['download_program'] !!}</span></i>
										<div class="clearfix"></div>
										<a href="javascript:void(0)" class="display-block remove_file_name"><i>Delete Currect File?</i></a>
									@endif
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-12">
						<h4>Images/Logos</h4>
						<hr>
						<div class="col-md-12 no-padding-left">
							<div class="col-md-3 " style="height: 34px; line-height: 34px;">
								{!! Form::label('site_logo', 'Site Logo:') !!}
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-preview thumbnail" >
											@if(isset($settings) && !empty($settings['site_logo']))
									  			<img src="{{$settings['site_logo']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
											@else
									  			<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
											@endif
									  	</div>
									  	<div>
									    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
									    		<span class="fileupload-new">Select image</span>
												{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
												{!! Form::hidden('site_logo', null, ['id' => 'thumbnail']); !!}
											</span>
									    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
									  		<div class="clearfix"></div>
									  	</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-12 no-padding-left">
							
							<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
								{!! Form::label('general_partner_logo', 'General Partner:') !!}
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-preview thumbnail" >
											@if(isset($settings) && !empty($settings['general_partner_logo']))
									  			<img src="{{$settings['general_partner_logo']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
											@else
									  			<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
											@endif
									  	</div>
									  	<div>
									    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
									    		<span class="fileupload-new">Select image</span>
												{{-- {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!} --}}
												{!! Form::hidden('general_partner_logo', null, ['id' => 'thumbnail']); !!}
											</span>
									    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
									  		<div class="clearfix"></div>
									  	</div>
									</div>
								</div>
							</div>
						</div>

						<div class="clearfix"></div>
						<div class="col-md-12 no-padding-left">
							
							<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
								{!! Form::label('general_partner_url', 'General Partner Url:') !!}
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class='input-group'>
									    <span class="input-group-addon">
									        <span class="fa fa-link"></span>
									    </span>
										{!! Form::text('general_partner_url', null, ['class' => 'form-control']) !!}
									</div>
								</div>
							</div>
						</div> -->
						<div class="clearfix"></div>
						<br>
						<hr>
						<div class="col-md-12">
							{!! Form::submit('Save Changes', ['class'=> 'btn btn-success btn-flat col-md-12']) !!}
						</div>
					</div>
					
					</div>

					{!! Form::close() !!}


	    		</div>
		    	<div id="item-example" style="display: none;">
		    		<div class="item">
		    			<div class="col-md-4">
		    				<div class="form-group">
		    					<div class='input-group'>
		    					    <span class="input-group-addon">
		    					        <span class="fa fa-search"></span>
		    					    </span>
		    					    <select name="social[0][name]" class="social_icon_name" style="width:100%">
		    							@include('admin-panel::layouts.parts._fontawesom_dropdown', ['selected' => ''])
		    					    </select>
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-7">
		    				<div class="form-group">
		    					<div class='input-group'>
		    					    <span class="input-group-addon">
		    					        <span class="fa fa-link"></span>
		    					    </span>
		    						{!! Form::text('social[0][url]', null, ['class' => 'form-control social_icon_url', 'placeholder' => 'Socioal Site Url', 'required']) !!}
		    					</div>
		    				</div>
		    			</div>
		    			<div class="col-md-1">
		    				<div class="form-group">
		    					<span class="fa fa-minus btn btn-danger btn-flat remove-row"></span>
		    				</div>
		    			</div>
		    		</div>
		    	</div>

		    </div>
		    <!-- /.box-footer-->
		</div>
		
	<div class="clearfix"></div>
@endsection

@section('script')

	<script>
		$(document).ready(function(){
		    /* Detect any change of option*/
		 	$("body").off('change', '.social_icon_name').on('change', '.social_icon_name', function(){
		 		var icon = $(this).val();
		 		console.log(icon)
		 		$(this).siblings('.input-group-addon').find('span').remove();
		 		$(this).siblings('.input-group-addon').append('<span class="fa'+icon+'"></span>').html('<i class="fa '+icon+'"></i>');
		 	});
		 	$("body").off('click', '.remove-row').on('click', '.remove-row', function(){
		 		$(this).closest('.item').remove();
		 	});
		 	$("body").off('click', '.add-social-row').on('click', '.add-social-row', function(){
		 		var container  = $('.social-icons-group');
		 		var item = $('#item-example').find('.item').find('.select2-container').remove();
		 		var item = $('#item-example').find('.item').clone();
		 		item.find('.social_icon_name').attr('name', "social["+$('.social_icon_name').length+"][name]");
		 		item.find('.social_icon_url').attr('name', "social["+$('.social_icon_url').length+"][url]");
		 		container.append(item);
		 		$('select').select2();
		 	});

		 });
	</script>
					
	<!-- DataTables -->
	<script src="{{ asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

@endsection

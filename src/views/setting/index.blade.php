@extends('admin-panel::layouts.app')
@section('style')
	<!-- DataTables -->
{{--	<link rel="stylesheet" href="{{ asset('admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">--}}
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
							{!! Form::label('site_email', 'Site Email:') !!}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-envelope"></span>
								    </span>
									{!! Form::text('site_email', null, ['class' => 'form-control', 'placeholder' => env('APP_EMAIL')]) !!}
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
								        <span class="fa fa-home"></span>
								    </span>
									{{ Form::select('index', $pages, isset($selected) && $selected != null ? $selected : null, ['placeholder' => 'Please Select']) }}
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
								        <span class="fa fa-globe"></span>
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
								        <span class="fa fa-language"></span>
								    </span>
									{{ Form::select('default_lang', $languages, isset($selected) && $selected != null ? $selected : null) }}
								</div>
							</div>
						</div>

					</div>
					
					<div class="col-md-12">
						<h4>Social links</h4>
						<hr>

						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
							{!! Form::label('facebook', 'Facebook:') !!}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-facebook"></span>
								    </span>
									{!! Form::text('facebook', null, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>

						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">
							{!! Form::label('insta', 'Instagram:') !!}
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group'>
								    <span class="input-group-addon">
								        <span class="fa fa-instagram"></span>
								    </span>
									{!! Form::text('insta', null, ['class' => 'form-control']) !!}
								</div>
							</div>
						</div>
{{--						<div class="social-icons-group">--}}
{{--							@if(isset($settings) && isset($settings['social']))--}}
{{--								@foreach($settings['social'] as $key => $value)--}}
{{--									<div class="item">--}}
{{--										<div class="col-md-4">--}}
{{--											<div class="form-group">--}}
{{--												<div class='input-group'>--}}
{{--												    <span class="input-group-addon">--}}
{{--												        <span class="fa {{ $value->name }}"></span>--}}
{{--												    </span>--}}
{{--												    <select name="social[{{$key}}][name]" class="social_icon_name">--}}
{{--														@include('admin-panel::layouts.parts._fontawesom_dropdown',['selected' => $value->name])--}}
{{--												    </select>--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										</div>--}}
{{--										<div class="col-md-7">--}}
{{--											<div class="form-group">--}}
{{--												<div class='input-group'>--}}
{{--												    <span class="input-group-addon">--}}
{{--												        <span class="fa fa-link"></span>--}}
{{--												    </span>--}}
{{--													{!! Form::text('social['.$key.'][url]', $value->url ?? null,  ['class' => 'form-control social_icon_url', 'placeholder' => 'Socioal Site Url', 'required']) !!}--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										</div>--}}
{{--										<div class="col-md-1">--}}
{{--											<div class="form-group">--}}
{{--												<span class="fa fa-minus btn btn-danger btn-flat remove-row"></span>--}}
{{--											</div>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--								@endforeach--}}
{{--							@endif--}}
{{--						</div>--}}
{{--						<a class="btn btn-success btn-flat pull-right add-social-row"> Add New Social Icon</a>--}}
						
						<div class="clearfix"></div>
					</div>



{{--					<div class="panel-group" id="accordion">--}}
{{--						@if(isset($settings) && isset($settings['options']))--}}
{{--							@foreach($settings['options'] as $key => $value)--}}
{{--						<div class="panel panel-default ">--}}
{{--							<div class="panel-heading"> <span class="glyphicon glyphicon-remove-circle pull-right "></span>--}}

{{--								<h4 class="panel-title">--}}
{{--									@php--}}
{{--										$id = time();--}}
{{--									@endphp--}}
{{--									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$id}}">--}}
{{--										Additional option--}}
{{--									</a>--}}
{{--								</h4>--}}

{{--							</div>--}}



{{--							<div id="{{$id}}" class="panel-collapse collapse">--}}
{{--								<div class="panel-body panel-count">--}}

{{--									<div class="form-group row">--}}
{{--										<div class="col-md-3">--}}
{{--											<label for="">Input Type</label>--}}
{{--											<select name="options[$key][type]" class="input_type option_type">--}}
{{--												<option value="" >Select type</option>--}}
{{--												<option value="text" >Text</option>--}}
{{--												<option value="image">Image</option>--}}
{{--												<option value="editor">Editor</option>--}}
{{--											</select>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--									<div class="form-group row">--}}
{{--										<div class="col-md-3 ">--}}
{{--											{!! Form::label('options['.$key.']['.$value->key.'', 'Key'); !!}--}}
{{--											{!! Form::text('options['.$key.']['.$value->key.']',  $value->key?? null, ['class' => 'form-control otpion_key type_label']); !!}--}}
{{--										</div>--}}
{{--										<div class="col-md-9">--}}
{{--											@if($value->type == 'text')--}}
{{--											<div class="form-group option-form text" style="display: none;">--}}
{{--												{!! Form::label('options['.$key.'][value]', 'Value'); !!}--}}
{{--												{!! Form::text('options['.$key.'][value]',  $value->value?? null, ['class' => 'form-control otpion_val', 'placeholder' => '']) !!}--}}
{{--											</div>--}}
{{--											@endif--}}
{{--											@if($value->type == 'editor')--}}
{{--											<div class="form-group option-form editor" style="display: none;">--}}
{{--												{!! Form::label('options['.$key.']['.$value->value.']', 'Content'); !!}--}}
{{--												{!! Form::textarea('options['.$key.']['.$value->value.']', $value->value?? null, ['class' => 'form-control editor otpion_val', 'id' => 'content', 'name' =>  'content']); !!}--}}
{{--											</div>--}}
{{--											@endif--}}

{{--											<div class="form-group option-form image" style="display: none;">--}}
{{--												{!! Form::label('thumbnail', 'Featured Image'); !!}--}}
{{--												<div class="fileupload fileupload-new" data-provides="fileupload">--}}
{{--													<div class="fileupload-preview thumbnail" style="width: 100%;">--}}
{{--														@if(isset($page) && !empty($page->thumbnail))--}}
{{--															<img src="{{$page->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">--}}
{{--														@else--}}
{{--															<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">--}}
{{--														@endif--}}
{{--													</div>--}}
{{--													<div>--}}
{{--														<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">--}}
{{--															<span class="fileupload-new">Select image</span>--}}
{{--															 {!! Form::file('options[0][value]', null, ['class' => 'form-control otpion_val']); !!}--}}
{{--															{!! Form::hidden('options[0][value]', null, ['id' => 'thumbnail']); !!}--}}
{{--														</span>--}}
{{--														<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>--}}
{{--														<div class="clearfix"></div>--}}
{{--													</div>--}}
{{--												</div>--}}
{{--											</div>--}}

{{--										</div>--}}

{{--									</div>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--							@endforeach--}}
{{--							@endif--}}
{{--					</div>--}}

{{--					<div class="panel panel-default template" style="display: none;">--}}
{{--						<div class="panel-heading"> <span class="glyphicon glyphicon-remove-circle pull-right "></span>--}}

{{--							<h4 class="panel-title">--}}
{{--								@php--}}
{{--									$id = time();--}}
{{--								@endphp--}}
{{--								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$id}}">--}}
{{--									Additional option--}}
{{--								</a>--}}
{{--							</h4>--}}

{{--						</div>--}}



{{--						<div id="{{$id}}" class="panel-collapse collapse" >--}}
{{--							<div class="panel-body panel-count">--}}

{{--								<div class="form-group row">--}}
{{--									<div class="col-md-3">--}}
{{--										<label for="">Input Type</label>--}}
{{--										<select name="options[0][type]" class="input_type option_type">--}}
{{--											<option value="" >Select type</option>--}}
{{--											<option value="text" >Text</option>--}}
{{--											--}}{{--												<option value="image">Image</option>--}}
{{--											<option value="editor">Editor</option>--}}
{{--										</select>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								<div class="form-group row">--}}
{{--									<div class="col-md-3 ">--}}
{{--										{!! Form::label('options[0][key]', 'Key'); !!}--}}
{{--										{!! Form::text('options[0][key]', null, ['class' => 'form-control otpion_key type_label']); !!}--}}
{{--									</div>--}}
{{--									<div class="col-md-9">--}}
{{--										<div class="form-group option-form text" style="display: none;">--}}
{{--											{!! Form::label('options[0][value]', 'Value'); !!}--}}
{{--											{!! Form::text('options[0][value]', null, ['class' => 'form-control otpion_val', 'placeholder' => '']) !!}--}}
{{--										</div>--}}

{{--										<div class="form-group option-form editor" style="display: none;">--}}
{{--											{!! Form::label('options[0][value]', 'Content'); !!}--}}
{{--											{!! Form::textarea('options[0][value]', null, ['class' => 'form-control editor otpion_val', 'id' => 'content', 'name' =>  'content']); !!}--}}
{{--										</div>--}}

{{--										--}}{{--											<div class="form-group option-form image" style="display: none;">--}}
{{--										--}}{{--												{!! Form::label('thumbnail', 'Featured Image'); !!}--}}
{{--										--}}{{--												<div class="fileupload fileupload-new" data-provides="fileupload">--}}
{{--										--}}{{--													<div class="fileupload-preview thumbnail" style="width: 100%;">--}}
{{--										--}}{{--														@if(isset($page) && !empty($page->thumbnail))--}}
{{--										--}}{{--															<img src="{{$page->thumbnail}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">--}}
{{--										--}}{{--														@else--}}
{{--										--}}{{--															<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">--}}
{{--										--}}{{--														@endif--}}
{{--										--}}{{--													</div>--}}
{{--										--}}{{--													<div>--}}
{{--										--}}{{--														<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">--}}
{{--										--}}{{--															<span class="fileupload-new">Select image</span>--}}
{{--										--}}{{--															 {!! Form::file('options[0][value]', null, ['class' => 'form-control otpion_val']); !!}--}}
{{--										--}}{{--															{!! Form::hidden('options[0][value]', null, ['id' => 'thumbnail']); !!}--}}
{{--										--}}{{--														</span>--}}
{{--										--}}{{--														<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>--}}
{{--										--}}{{--														<div class="clearfix"></div>--}}
{{--										--}}{{--													</div>--}}
{{--										--}}{{--												</div>--}}
{{--										--}}{{--											</div>--}}

{{--									</div>--}}

{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}


{{--					<br />--}}
{{--					--}}{{--    <button class="btn btn-lg btn-primary btn-add-panel pull-right"> <i class="glyphicon glyphicon-plus"></i> Add new panel</button>--}}
{{--					<a href="#" class="pull-right  btn-add-panel" ><i class="fa fa-plus"></i> Add New Option</a>--}}




{{--				 <div class="col-md-12">--}}
{{--						<h4>Links</h4>--}}
{{--						<hr>--}}
{{--						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">--}}
{{--							{!! Form::label('submit_film_link', 'Submit a Film Link:') !!}--}}
{{--						</div>--}}
{{--						<div class="col-md-9">--}}
{{--							<div class="form-group">--}}
{{--								<div class='input-group'>--}}
{{--								    <span class="input-group-addon">--}}
{{--								        <span class="fa fa-link"></span>--}}
{{--								    </span>--}}
{{--									{!! Form::text('submit_film_link', null, ['class' => 'form-control']) !!}--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--						<div class="clearfix"></div>--}}
{{--					</div>--}}
{{--					<div class="col-md-12">--}}
{{--						<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">--}}
{{--							{!! Form::label('download_program', 'Downloadble Program:') !!}--}}
{{--						</div>--}}
{{--						<div class="col-md-9">--}}
{{--							<div class="form-group">--}}
{{--								<div class='input-group download_file_container'>--}}
{{--									{!! Form::file('download_program', null, ['class' => 'form-control']) !!}--}}
{{--									@if(isset($settings['download_program']) && !empty($settings['download_program']))--}}
{{--										{!! Form::hidden('download_program_file_name', $settings['download_program'], ['class' => 'file_name']) !!}--}}
{{--										<i>Current File: <span class="file_name_text">{!! $settings['download_program'] !!}</span></i>--}}
{{--										<div class="clearfix"></div>--}}
{{--										<a href="javascript:void(0)" class="display-block remove_file_name"><i>Delete Currect File?</i></a>--}}
{{--									@endif--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--						<div class="clearfix"></div>--}}
{{--					</div>--}}
					<div class="col-md-12">
						<h4>Contacts</h4>
						<hr>
						<div class="col-md-9">
							<div class="form-group">
								<div class='input-group'>
										<span class="input-group-addon">
											<span class="fa fa-phone"></span>
										</span>
									{!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' =>'(022)22-22-22']) !!}
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<div class='input-group'>
									<div class="form-group">
										<div class="form-group">
											{!! Form::label('contacts', 'Contacts'); !!}
											{!! Form::textarea('contacts', null, ['class' => 'form-control ckeditor', 'id' => 'content', 'name' =>  'content']); !!}
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<div class='input-group'>
									<div class="form-group">
										{!! Form::label('footer_text', 'Footer Text'); !!}
										{!! Form::textarea('footer_text', null, ['class' => 'form-control ckeditor', 'id' => 'footer_text', 'name' =>  'content']); !!}
									</div>
								</div>
							</div>
						</div>

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
												 {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!}
												{!! Form::hidden('site_logo', null, ['id' => 'thumbnail']); !!}
											</span>
									    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
									  		<div class="clearfix"></div>
									  	</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 no-padding-left">
							<div class="col-md-3 " style="height: 34px; line-height: 34px;">
								{!! Form::label('banner', 'Main Banner:') !!}
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-preview thumbnail" >
											@if(isset($settings) && !empty($settings['banner']))
												<img src="{{$settings['banner']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">
											@else
												<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">
											@endif
										</div>
										<div>
									    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
									    		<span class="fileupload-new">Select image</span>
												 {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!}
												{!! Form::hidden('banner', null, ['id' => 'thumbnail']); !!}
											</span>
											<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<div class='input-group'>
									<div class="form-group">
										{!! Form::label('product_banner', 'Product Page Banner'); !!}
										{!! Form::textarea('product_banner', null, ['class' => 'form-control ckeditor', 'id' => 'product_banner', 'name' =>  'product_banner']); !!}
									</div>
								</div>
							</div>
						</div>

						<div class="clearfix"></div>


{{--						<div class="col-md-12 no-padding-left">--}}

{{--							<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">--}}
{{--								{!! Form::label('general_partner_logo', 'General Partner:') !!}--}}
{{--							</div>--}}
{{--							<div class="col-md-4">--}}
{{--								<div class="form-group">--}}
{{--									<div class="fileupload fileupload-new" data-provides="fileupload">--}}
{{--										<div class="fileupload-preview thumbnail" >--}}
{{--											@if(isset($settings) && !empty($settings['general_partner_logo']))--}}
{{--									  			<img src="{{$settings['general_partner_logo']}}" class="img-responsive" alt="" onerror="imgError(this);" id="thumbnail-image">--}}
{{--											@else--}}
{{--									  			<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image">--}}
{{--											@endif--}}
{{--									  	</div>--}}
{{--									  	<div>--}}
{{--									    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">--}}
{{--									    		<span class="fileupload-new">Select image</span>--}}
{{--												 {!! Form::file('thumbnail', null, ['class' => 'form-control']); !!}--}}
{{--												{!! Form::hidden('general_partner_logo', null, ['id' => 'thumbnail']); !!}--}}
{{--											</span>--}}
{{--									    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>--}}
{{--									  		<div class="clearfix"></div>--}}
{{--									  	</div>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}

{{--						<div class="clearfix"></div>--}}
{{--						<div class="col-md-12 no-padding-left">--}}

{{--							<div class="col-md-3 no-padding-left" style="height: 34px; line-height: 34px;">--}}
{{--								{!! Form::label('general_partner_url', 'General Partner Url:') !!}--}}
{{--							</div>--}}
{{--							<div class="col-md-4">--}}
{{--								<div class="form-group">--}}
{{--									<div class='input-group'>--}}
{{--									    <span class="input-group-addon">--}}
{{--									        <span class="fa fa-link"></span>--}}
{{--									    </span>--}}
{{--										{!! Form::text('general_partner_url', null, ['class' => 'form-control']) !!}--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</div>--}}
{{--						</div>--}}
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
	<script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>

	<script>

		$(function () {
			if($('.editor').length > 0){
				CKEDITOR.replaceClass('.editor');
			}
		})
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

	<script>
		$(document).ready(function() {
			$('body').off('change', '.option_type').on('change', '.option_type', function () {

				if($(this).val() == "text") {
					$(this).closest('.panel-body').find('.option-form').hide();
					$(this).closest('.panel-body').find('.option-form.text').show();
				} else if($(this).val() == "editor") {
					$(this).closest('.panel-body').find('.option-form').hide();
					$(this).closest('.panel-body').find('.option-form.editor').show();
					$(function () {
						if($('.editor').length > 0){
							CKEDITOR.replaceClass('.editor');
						}
					})

				} else if($(this).val() =='image' ){
					$(this).closest('.panel-body').find('.option-form').hide();
					$(this).closest('.panel-body').find('.option-form.image').show();
				}
			});
			var $template = $(".template");

			$(".btn-add-panel").on("click", function (e) {
				e.preventDefault();
				if ($('.clone-hint').length > 0) {
					if ($('.clone-hint').length > 1) {
						var type = $('.clone-hint').last().find('.input_type').val();
						var label = $('.clone-hint').last().find('.type_label').val();
					} else {
						var type = $('.template').last().prev().find('.input_type').val();
						var label = $('.template').last().prev().find('.type_label').val();
					}
				} else {
					var type = $('.template').last().find('.input_type').val();
					var label = $('.template').last().find('.type_label').val();
				}


				var $newPanel = $template.clone();
				$('.panel-collapse').each(function () {
					$(this).removeClass('in');
				})
				var id = Date.now();
				$newPanel.find(".collapse").removeClass("in");
				$newPanel.find(".accordion-toggle").attr("href", "#" + id).text('Additional option');
				$newPanel.find(".panel-collapse").attr("id", id).addClass('in');
				$newPanel.find('.otpion_key').attr('name', "options["+$('.panel-count').length+"][key]");
				$newPanel.find('.otpion_val').attr('name', "options["+$('.panel-count').length+"][value]");
				$newPanel.find('.option_type').attr('name', "options["+$('.panel-count').length+"][type]");
				console.log($newPanel.find('.option_type'));
				$("#accordion").append($newPanel.fadeIn());
				$newPanel.find('.select2-container').remove()
				$("select").select2();
				$newPanel.find('input').val('');

			});
			$('body').off('change', '.input_type').on('change', '.input_type', function () {

				if ($(this).val() == "select") {
					$(this).closest('.panel-body').find('#multiple').parent().show().attr('disabled', false);
				} else {
					$(this).closest('.panel-body').find('#multiple').parent().hide().attr('disabled', 'disabled');
				}

				if (['select', 'checkbox', 'radio'].includes($(this).val())) {
					$(this).closest('.panel-body').find('.type_options').parent().show().attr('disabled', false);
				} else {
					$(this).closest('.panel-body').find('.type_options').parent().hide().attr('disabled', 'disabled');
				}
				$(this).closest('.panel-body').find('.type').val($(this).val());

			});
			$('body').off('blur', '.type_label').on('blur', '.type_label', function () {
				$(this).closest('.panel-body').find('.type_name').val(toSnakeCase($(this).val()));
				$(this).closest('.panel-collapse').siblings('.panel-heading').find('.accordion-toggle').text($(this).val());

			})

			$(document).on('click', '.glyphicon-remove-circle', function () {
				$(this).parents('.panel').get(0).remove();
			});


			toSnakeCase = function (string) {
				var s;
				s = string.replace(/[^\w\s]/g, "");
				s = s.replace(/\s+/g, " ");
				return s.toLowerCase().split(' ').join('_');
			};


			// function validateForm() {
			// 	$('.type_label').css("border", "1px solid");
			// 	$('.input_type').css("border", "1px solid");
			//
			// 	let type = $('.template').last().find('.input_type').val();
			// 	let label = $('.template').last().find('.type_label').val();
			// 	let type_opts = $('.template').last().find('.type_options').val();
			// 	if (type == "" && label != "") {
			// 		$('.template').last().find('.select2-selection').css("border", "1px solid red");
			// 		setTimeout(function () {
			// 			alert("Please select input type");
			// 		}, 100);
			// 		return false;
			// 	}
			// 	if (label == "" && type != "") {
			// 		$('.template').last().find('.type_label').css("border", "1px solid red");
			// 		setTimeout(function () {
			// 			alert("Please select label for input");
			// 		}, 100);
			// 		return false;
			// 	}
			//
			// 	if (['select', 'checkbox', 'radio'].includes(type) && type_opts == "") {
			// 		$('.template').last().find('.type_options').css("border", "1px solid red");
			// 		setTimeout(function () {
			// 			alert("Please fill options for input");
			// 		}, 100);
			// 		return false;
			// 	}
			// 	return true;
			// }

			// $('form').one('submit', function (e) {
			// 	e.preventDefault();
			// 	var additional_opts = {};
			// 	$('.panel-body').each(function () {
			// 		additional_opts[$(this).parent().attr('id')] = $(this).find('input').serialize();
			// 	});
			// 	$('#additional_options').val(JSON.stringify(additional_opts));
			// 	// if (!validateForm()) {
			// 	// 	return
			// 	// }
			// 	;
			// 	$(this).submit();
			// })
		})
	</script>
					
	<!-- DataTables -->
{{--	<script src="{{ asset('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>--}}
{{--	<script src="{{ asset('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>--}}

@endsection

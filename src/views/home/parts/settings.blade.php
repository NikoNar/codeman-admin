<div class="box">
    <div class="box-body">
		{!! Form::open(['action' => "Admin\HomeController@updateSettings", 'method' => 'POST', 'id' => 'home-page-settings-form']) !!}
	    	<div class="col-md-12">
		    	<h3 class="pull-left" style="margin: 0">Home Page Settings</h3>
	    	</div>
	    	<div class="clearfix"></div>
	    	<hr>
	    	<div  class="col-md-12">
	    		<div class="form-group">
	    			<h4>News Section</h4>
	    			<label for="">Show News:</label>
	    			<div class="onoffswitch pull-right">
	    			    <input type="checkbox" name="homepage_settings[show_news]" class="onoffswitch-checkbox" id="news_section" value="1" @if(isset($home_settings->show_news)) checked @endif>
	    			    <label class="onoffswitch-label" for="news_section">
	    			        <span class="onoffswitch-inner"></span>
	    			        <span class="onoffswitch-switch"></span>
	    			    </label>
	    			</div>
	    			<div class="clearfix"></div>
	    		</div>
	    		<hr>
	    		<div class="form-group">
	    			<h4>Agenda Section</h4>
	    			<div class="col-md-12 no-padding">
	    				<label for="">Show Agenda:</label>
	    				<div class="onoffswitch pull-right">
	    				    <input type="checkbox" name="homepage_settings[show_agenda]" class="onoffswitch-checkbox" id="agenda_section" value="1"  @if(isset($home_settings->show_agenda)) checked @endif>
	    				    <label class="onoffswitch-label" for="agenda_section">
	    				        <span class="onoffswitch-inner"></span>
	    				        <span class="onoffswitch-switch"></span>
	    				    </label>
	    				</div>
	    			</div>
					<div class="col-md-12 no-padding">
						
						<div class="clearfix"></div>
						{!! Form::label('thumbnail', 'Background Image'); !!}
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 100%;">
								@if(isset($home_settings) && !empty($home_settings->agenda_bg))
						  			<img src="{{$home_settings->agenda_bg}}" class="img-responsive thumbnail-image" alt="" onerror="imgError(this);" id="thumbnail-image" style="height: 150px; object-fit: contain;">
								@else
						  			<img src="{{ asset('admin-panel/images/no-image.jpg')}}" class="img-responsive thumbnail-image" alt="No Featured Image" onerror="imgError(this);" id="thumbnail-image" style="height: 150px; object-fit: contain;">
								@endif
						  	</div>
						  	<div>
						    	<span class="btn btn-file btn-primary btn-flat col-md-6 media-open">
						    		<span class="fileupload-new">Select image</span>
									{!! Form::hidden('homepage_settings[agenda_bg]', isset($home_settings->agenda_bg) ? $home_settings->agenda_bg : null, ['id' => 'thumbnail', 'class' => 'thumbnail']); !!}
								</span>
						    	<a href="javascript:void(0)" class="btn fileupload-exists btn-danger btn-flat col-md-6 remove-thumbnail" data-dismiss="fileupload" id="remove-thumbnail">Remove</a>
						  		<div class="clearfix"></div>
						  	</div>
						</div>
					</div>
					<div class="clearfix"></div>
	    		</div>
	    		<hr>
	    		<div class="form-group">
	    			<h4>Gallery Section</h4>
	    			<label for="">Show Gallery:</label>
	    			<div class="onoffswitch pull-right">
	    			    <input type="checkbox" name="homepage_settings[show_gallery]" class="onoffswitch-checkbox" id="gallery_section" value="1"  @if(isset($home_settings->show_gallery)) checked @endif>
	    			    <label class="onoffswitch-label" for="gallery_section">
	    			        <span class="onoffswitch-inner"></span>
	    			        <span class="onoffswitch-switch"></span>
	    			    </label>
	    			</div>
	    			<div class="clearfix"></div>
	    		</div>
	    		<hr>
	    		<div class="form-group">
	    			<h4>Video Section</h4>
	    			<label for="">Show Videos:</label>
	    			<div class="onoffswitch pull-right">
	    			    <input type="checkbox" name="homepage_settings[show_video]" class="onoffswitch-checkbox" id="video_section" value="1" @if(isset($home_settings->show_video)) checked @endif>
	    			    <label class="onoffswitch-label" for="video_section">
	    			        <span class="onoffswitch-inner"></span>
	    			        <span class="onoffswitch-switch"></span>
	    			    </label>
	    			</div>
	    			<div class="clearfix"></div>
	    		</div>
	    		<hr>
	    		<div class="form-group">
	    			<h4>Daily Section</h4>
	    			<label for="">Show Dailies:</label>
	    			<div class="onoffswitch pull-right">
	    			    <input type="checkbox" name="homepage_settings[show_daily]" class="onoffswitch-checkbox" id="daily_section" value="1" @if(isset($home_settings->show_daily)) checked @endif>
	    			    <label class="onoffswitch-label" for="daily_section">
	    			        <span class="onoffswitch-inner"></span>
	    			        <span class="onoffswitch-switch"></span>
	    			    </label>
	    			</div>
	    			<div class="clearfix"></div>
	    		</div>
	    		<hr>
	    		<div class="form-group">
	    			<h4>Sponsors Section</h4>
	    			<label for="">Show Sponsors:</label>
	    			<div class="onoffswitch pull-right">
	    			    <input type="checkbox" name="homepage_settings[show_sponsors]" class="onoffswitch-checkbox" id="sponsors_section" value="1" @if(isset($home_settings->show_sponsors)) checked @endif>
	    			    <label class="onoffswitch-label" for="sponsors_section">
	    			        <span class="onoffswitch-inner"></span>
	    			        <span class="onoffswitch-switch"></span>
	    			    </label>
	    			</div>
	    			<div class="clearfix"></div>
	    		</div>
	    	</div>
	    	<div class="clearfix"></div>
	    	<hr>
	    	<div  class="col-md-12">
	    		{!! Form::submit('Save Changes', ['class' => 'btn btn-success btn-flat pull-right']) !!}
	    	</div>
		{!! Form::close() !!}
	</div>
</div>
<div class="box">
    <div class="box-body">
		{!! Form::open(['action' => "Admin\HomeController@updateGridView", 'method' => 'POST', 'id' => 'grid-view-form']) !!}
    	<div class="col-md-12">
	    	<h3 class="pull-left" style="margin: 0">Home Grid View</h3>
    		{!! Form::submit('Save Changes', ['class' => 'btn btn-success btn-flat pull-right', 'id' => 'save-grid-view']) !!}
    		<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-right add-stock-item" style="margin-right: 20px;">
    			<i class="fa fa-plus"></i> Add Element
    		</a>
    	</div>
		<div class="clearfix"></div>
    	<hr>
    	<div class="col-md-12">
			<div class="grid-stack">
				@if(isset($gridView) && !$gridView->isEmpty())
					@foreach($gridView as $item)
						<div class="grid-stack-item" data-id="{!! $item->id !!}" data-gs-x="{!! $item->x !!}" data-gs-y="{!! $item->y !!}" data-gs-width="{!! $item->w !!}" data-gs-height="{!! $item->h !!}">
							@php
								$options = (array) json_decode($item->options);
								$bg_image_style = null;
								if(isset($options['thumbnail']) && !empty($options['thumbnail'])) {
									$bg_image_style = 'style=background-image:url("'.$options['thumbnail'].'")';
								}
							@endphp
							<div class="grid-stack-item-content">
								<a href="javascript:void(0)" class="img-change media-open" {{ $bg_image_style }}>
									<i class="fa fa-camera"></i>
									<input name="item[{!! $item->id !!}][thumbnail]" type="hidden" class="thumbnail" value="{!! $options['thumbnail'] !!}" >
								</a>
							</div>
							<input name="item[{!! $item->id !!}][x]" type="hidden" value="{!! $item->x !!}">
							<input name="item[{!! $item->id !!}][y]" type="hidden" value="{!! $item->y !!}">
							<input name="item[{!! $item->id !!}][w]" type="hidden" value="{!! $item->w !!}">
							<input name="item[{!! $item->id !!}][h]" type="hidden" value="{!! $item->h !!}">

							<input name="item[{!! $item->id !!}][top-text-en]" type="hidden" value="{!! $options['top-text-en'] !!}">
							<input name="item[{!! $item->id !!}][bottom-text-en]" type="hidden" value="{!! $options['bottom-text-en'] !!}">
							<input name="item[{!! $item->id !!}][url-en]" type="hidden" value="{!! $options['url-en'] !!}">
							<input name="item[{!! $item->id !!}][top-text-arm]" type="hidden" value="{!! $options['top-text-arm'] !!}">
							<input name="item[{!! $item->id !!}][bottom-text-arm]" type="hidden" value="{!! $options['bottom-text-arm'] !!}">
							<input name="item[{!! $item->id !!}][url-arm]" type="hidden" value="{!! $options['url-arm'] !!}">
							<input name="item[{!! $item->id !!}][url-action]" type="hidden" value="{!! $options['url-action'] !!}">

							<span class="remove-item">x</span>
							<span class="setting-item"><i class="fa fa-cog"></i></span>
						</div>
					@endforeach
				@else
					<div class="grid-stack-item" data-id="1" data-gs-x="0" data-gs-y="0" data-gs-width="1" data-gs-height="1">
						<div class="grid-stack-item-content">
							<a href="javascript:void(0)" class="img-change media-open">
								<i class="fa fa-camera"></i>
								<input name="item[1][thumbnail]" type="hidden" class="thumbnail" value="" >
							</a>
						</div>
						<input name="item[1][x]" type="hidden" value="">
						<input name="item[1][y]" type="hidden" value="">
						<input name="item[1][w]" type="hidden" value="">
						<input name="item[1][h]" type="hidden" value="">

						<input name="item[1][top-text-en]" type="hidden" value="">
						<input name="item[1][bottom-text-en]" type="hidden" value="">
						<input name="item[1][url-en]" type="hidden" value="">
						<input name="item[1][top-text-arm]" type="hidden" value="">
						<input name="item[1][bottom-text-arm]" type="hidden" value="">
						<input name="item[1][url-arm]" type="hidden" value="">
						<input name="item[1][url-action]" type="hidden" value="">

						<span class="remove-item">x</span>
						<span class="setting-item"><i class="fa fa-cog"></i></span>
					</div>
				@endif
			</div>
    	</div>

		{!! Form::close() !!}
    </div>

    <div id="drid-item-popup" class="modal fade" role="dialog">
    	<div class="modal-dialog modal-lg">
    		<!-- Modal content-->
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Item Settings</h4>
    			</div>
    			<div class="modal-body">
    				<div class="grid-item-settings">
    				    <div class="box-body">
				    		<form id="grid-item-settings-form">
				    			<input type="hidden" name="item-id" value="">
	    				    	<div class="col-md-6">
	    				    		<h4>English</h4>
	    				    		<hr>	
		    				    	<div class="form-group">
		    				    		<label for="top-text-en">Text On Top Of</label>
		    				    		<input type="text" name="top-text-en" id="top-text-en" class="form-control">
		    				    	</div>
		    				    	<div class="form-group">
		    				    		<label for="bottom-text-en">Text On Bottom Of</label>
		    				    		<input type="text" name="bottom-text-en" id="bottom-text-en" class="form-control">
		    				    	</div>
		    				    	<div class="form-group">
		    				    		<label for="bottom-text">On click URL</label>
		    				    		<input type="text" name="url-en" id="url-en" class="form-control">
		    				    	</div>
	    				    	</div>
	    				    	<div class="col-md-6">
	    				    		<h4>Armenian</h4>
	    				    		<hr>	

		    				    	<div class="form-group">
		    				    		<label for="top-text-en">Text On Top Of</label>
		    				    		<input type="text" name="top-text-arm" id="top-text" class="form-control">
		    				    	</div>
		    				    	<div class="form-group">
		    				    		<label for="bottom-text">Text On Bottom Of</label>
		    				    		<input type="text" name="bottom-text-arm" id="bottom-text" class="form-control">
		    				    	</div>
		    				    	<div class="form-group">
		    				    		<label for="bottom-text">On click URL</label>
		    				    		<input type="text" name="url-arm" id="url" class="form-control">
		    				    	</div>
	    				    	</div>
	    				    	<div class="clearfix"></div>
	    				    	<hr>
	    				    	<div class="form-group col-md-12">
	    				    		<label for="url-action">On click Action</label>
	    				    		<br>
	    				    		<select name="url-action" id="url-action" class="form-control" style="width:300px">
	    				    			<option value="_self">Open the link in the current window</option>
	    				    			<option value="_blank">Opens the linked document in a new tab</option>
	    				    		</select>
	    				    	</div>
				    		</form>
    				    </div>
    				</div>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-success save-grid-item-settings">Save</button>
    				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
    			</div>
    		</div>
    	</div>
    </div>
    <!-- /.box-footer-->
</div>
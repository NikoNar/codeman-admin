<div class="widget-settings">
    <div class="box-body">
		<form id="grid-item-settings-form">
	    	<div class="col-md-12">
	    		<h4>Image Properties</h4>
	    		<hr>	
	    	</div>
	    	<div class="col-md-4">
		    	<div class="form-group">
		    		<label for="position">Image Position</label>
		    		<h5>Horizontal</h5>
		    		<label for="position-left" style="margin-right: 10px">
		    			<input type="radio" id="position-left" class="form-control" name="position[x]" value="left">
		    			Left
		    		</label>
		    		<label for="position-x-center" style="margin-right: 10px">
		    			<input type="radio" id="position-x-center" class="form-control" name="position[x]" value="center">
		    			Center
		    		</label>
		    		<label for="position-right">
		    			<input type="radio" id="position-right" class="form-control" name="position[x]" value="right">
		    			{{ Form::radio('position[x]', 'right',
		    			 isset($options['position']) && isset($options['position']->x) && $options['position']->x == 'right' ? true : null,
		    			 ['id' => 'position-right']) }} 
		    			Right
		    		</label>
		    	</div>
		    	<div class="form-group">
		    		<h5>Vertical</h5>
		    		<label for="position-top" style="margin-right: 10px">
		    			<input type="radio" id="position-top" class="form-control" name="position[y]" value="top">
		    			Top 
		    		</label>
		    		<label for="position-y-center" style="margin-right: 10px">
		    			<input type="radio" id="position-y-center" class="form-control" name="position[y]" value="center"> 
		    			Center
		    		</label>
		    		<label for="position-bottom">
		    			<input type="radio" id="position-bottom" class="form-control" name="position[y]" value="bottom">
		    			Bottom
		    		</label>
		    	</div>
	    	</div>
	    	<div class="col-md-8">
	    		<div class="col-md-6">
		    		<div class="form-group">
		    			<label for="cta_url">Enter image CTA URL</label>
		    			<input type="text" id="cta_url" class="form-control" name="cta_url" value="">
		    		</div>
	    		</div>
	    		<div class="col-md-12">
	    			<div class="form-group">
	    				<label for="alt">Image ALT text</label>
	    				<input type="text" id="alt" class="form-control" name="alt" value="">
	    			</div>
	    		</div>
	    	</div>
	    	<div class="clearfix"></div>
		</form>
    </div>
</div>

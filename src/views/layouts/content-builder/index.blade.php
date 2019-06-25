<script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css" rel="stylesheet"
      />
<section id="content-builder">
	<div class="box">
	    <div class="box-header with-border">
	        <h3 class="box-title">Content Builder</h3>
	    </div>
	    <div class="box-body">
	    	<div id="builder-heading">
	    		<ul id="content-elements" class="elements-list">
	    			@if(isset($type) && $type == 'blog')
		    			<li class="content-element" id="b-image">
		    				<span class="glyphicon fa fa-image" aria-hidden="true"></span>
		    				<span class="glyphicon-class">Image</span>
		    			</li>
		    			<li class="content-element" id="b-text">
		    				<span class="glyphicon fa fa-align-center" aria-hidden="true"></span>
		    				<span class="glyphicon-class">Text</span>
	    				</li>
	    				<li class="content-element" id="b-text-2images">
		    				<span class="glyphicon fa fa-image" aria-hidden="true"></span>
		    				<span class="glyphicon-class">2 images</span>
	    				</li>

	    			@else
	    			<li class="content-element" id="b-slider">
	    				<div class="icon-section">
		    				<span class="glyphicon fa fa-images" aria-hidden="true"></span>
		    				<span class="glyphicon-class">Slider</span>
	    				</div>
	    			</li>

	    			<!-- <li class="content-element" id="b-image">
	    				<span class="glyphicon fa fa-image" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Image</span>
	    			</li> -->

	    			<li class="content-element" id="b-text">
	    				<span class="glyphicon fa fa-align-center" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Text</span>
	    			</li>
	    			<!-- <li class="content-element" id="b-info-block">
	    				<span class="glyphicon fa fa-info-circle" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Information Block</span>
	    			</li> -->
	    			<!-- <li class="content-element" id="b-image-right">
	    				<span class="glyphicon fa fa-align-center" aria-hidden="true"></span>
	    				<span class="glyphicon fa fa-image" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Image Right</span>
	    			</li>
	    			<li class="content-element" id="b-image-left">
	    				<span class="glyphicon fa fa-image" aria-hidden="true"></span>
	    				<span class="glyphicon fa fa-align-center" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Image Left</span>
	    			</li> -->
	    			<!-- <li class="content-element" id="b-offer-block">
	    				<span class="glyphicon fa fa-th" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Offers Block</span>
	    			</li>
	    			<li class="content-element" id="b-products-block">
	    				<span class="glyphicon fa fa-shopping-bag" aria-hidden="true"></span>
	    				<span class="glyphicon-class">Products Block</span>
	    			</li> -->
	    			@endif
	    		</ul>
	    	</div>
	    	<div class="clearfix"></div>
	    	<hr>
	    	<div id="builder-content" class="empty">

	    	</div>
	    </div>
	</div>
</section>

{{-- Settings Modal --}}
<div class="modal" id="block-settings-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body settings-options">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-flat" id="options-save" data-option="b-products-block" data-element-id="">Save changes</button>
				<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="attach-link" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Attach link</h4>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="link"></label>
					<input type="text" class="form-control slide_link" id="link" placeholder="http://example.com">
				</div>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save_link">Attach</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
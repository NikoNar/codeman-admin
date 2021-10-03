<script src='{{ asset('admin-panel/js/dragula/dragula.min.js') }}'></script>
<link href="{{ asset('admin-panel/js/dragula/dragula.min.css') }}" rel="stylesheet"
      />
<section id="content-builder">
	<div class="box">
	    <div class="box-header with-border">
	        <h3 class="box-title">Content Builder</h3>
	    </div>
	    <div class="box-body">
	    	@include('admin-panel::page.content-builder.parts._widget_items')
	    	<div class="clearfix"></div>
	    	<hr>
	    	<div id="builder-content" class="empty">

	    	</div>
	    </div>
	</div>
</section>

{{-- Settings Modal --}}
<div class="modal" id="block-settings-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
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
					<label for="link">Button Name</label>
					<input type="text" class="form-control slide_name" id="button_name" placeholder="">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="link">URL</label>
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
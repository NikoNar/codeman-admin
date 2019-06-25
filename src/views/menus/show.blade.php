@extends('admin.layouts.app')

@section('style')
@endsection

@section('content')
	<section class="content">
		<div class="row">
			<div class="col-md-3 no-padding-left">
				<div class="box box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Pages</h3>
							<div class="box-tools">
                				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              				</div>
            		</div>
            		<div class="box-body no-padding" style="">

              			<ul id="unused-menu-items" class="nav nav-pills nav-stacked">
              				@foreach($pages as $key => $page) 
              						<li class="dd-item active" data-id="{{$key }}">
              						   <a href="javascript:void(0)"> <div class="dd-handle">
              						    	{!! $page !!}
              						    </div></a>
              						</li>
              					</li>
	              			@endforeach
              			</ul>
            		</div>
            		<!-- /.box-body -->
          		</div>
	          	<!-- /. box -->
	          	<div class="box box-solid">
	            	<div class="box-header with-border">
	              		<h3 class="box-title">Labels</h3>

	              		<div class="box-tools">
	                		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
	              		</div>
	            	</div>
	            	<div class="box-body no-padding" style="">
		              	<ul class="nav nav-pills nav-stacked">
		                	<li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
		                	<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
		                	<li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
		              	</ul>
	            	</div>
	            	<!-- /.box-body -->
	          	</div>
	          <!-- /.box -->
        	</div>
        	<!-- /.col -->
	        <div class="col-md-6">
	          	<div class="box box-primary">
	           		<div class="box-header with-border">
	   		            <h3 class="box-title">Menu</h3>

   		              	<div class="box-tools pull-right">
   		              	</div>
	   		              <!-- /.box-tools -->
	   		        </div>
	   		        <div class="box-body">
	   		        	<h4> Menu Structure <small>Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</small>
	   		        	</h4>
						
						<div class="dd" id="nestable">
						    <ol class="dd-list " id="used-menu-items">
						      <p class="drug-drop-info"><i class="fa fa-arrows"></i>Drug and Drop Menu Items</p>
						    </ol>
						</div>
	   		        </div>
	          	</div>
	          	<!-- /. box -->
	        </div>
        	<!-- /.col -->
			<div class="col-md-3 no-padding-right">
				<div class="box box-solid">
					<div class="box-header with-border">
						<h3 class="box-title">Add/Edit Menu Item</h3>
							<div class="box-tools">
                				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              				</div>
            		</div>
            		<div class="box-body" style="">
            			<form action="">
	            			<div class="form-group">
	            				<input type="text" name="title" id="menu-item-title" class="form-control" placeholder="Title">
	            			</div>
	            			
	            			<div class="form-group">
	            				<input type="text" name="url" id="menu-item-url" class="form-control" placeholder="Url">
	            			</div>
	            			<div class="form-group">
	            				<label for="menu-item-target-blank"><input type="checkbox" name="target_blank" id="menu-item-target-blank"> Open link in a new tab</label>
	            			</div>

	            			<div class="form-group">
	            				<input type="text" name="additional-class" id="menu-item-calss-name" class="form-control" placeholder="Additional Class Name">
	            			</div>
	            			<div class="form-group">
	            				<input type="button" class="btn btn-primary btn-flat form-control" value="Save" >
	            			</div>
            			</form>
            		</div>
            		<!-- /.box-body -->
          		</div>
	          	<!-- /. box -->
        	</div>
      	</div>
      <!-- /.row -->
    </section>
@endsection


@section('script')
	<script src="{{ asset('admin-panel/dragula-master/dist/dragula.min.js') }}"></script>
	<script src="{{ asset('admin-panel/nestable-master/jquery.nestable.js') }}"></script>
	<script>
		dragula([document.getElementById('unused-menu-items'), document.getElementById('used-menu-items'),document.getElementById('structure-of-menu-items') ])
		  .on('drag', function (el) {
		    el.className = el.className.replace('ex-moved', '');
		  }).on('drop', function (el) {
		  	$('.drug-drop-info').remove();
		    el.className += ' ex-moved';
		  }).on('over', function (el, container) {
		    container.className += ' ex-over';
		  }).on('out', function (el, container) {
		    container.className = container.className.replace('ex-over', '');
		  });
		$('#nestable').nestable({ /* config options */ });
	</script>
@endsection
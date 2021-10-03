<div class="clearfix"></div>
<hr>
<div class="row">
	<div class="col-md-12">
		<div class="collapse" id="big-filter" style="margin-top: -20px;">
		  <div class="card card-body">
	    			<div class="clearfix"></div>
	    			<div class="box" style="background-color: #f9f9f9;">
	    			    <div class="box-header with-border">
	    			        <h3 class="box-title">Data Filters</h3>
	    			        <div class="box-tools pull-right">
	    			            <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#big-filter" >
	    			            	<i class="fa fa-minus"></i>
	    			            </button>
	    			            <div class="btn-group">
		    			            <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		    			                <i class="fa fa-wrench"></i>
		    			            </button>
	    			                <ul class="dropdown-menu" role="menu">
	    			                	<li class="form-group col-md-12">
	    			                		<label for="">Search Operator</label>
	    			                		<label for="search-operator-and" style="margin-right: 10px">
	    			                			AND
	    			                			<input type="radio" name="data-filter-search-operator" value="AND" id="search-operator-and" checked>
	    			                		</label>
	    			                		<label for="search-operator-or">
	    			                			OR
	    			                			<input type="radio" name="data-filter-search-operator" value="OR" id="search-operator-or">
	    			                		</label>
	    			                	</li>
	    			                    <li class="divider"></li>
	    			                    {{-- <li><a href="#">Separated link</a></li> --}}
	    			                </ul>
		    			        </div>
	    			        </div>
	    			    </div>
	    			    <!-- /.box-header -->
	    			    <div class="box-body" >
	    			        <div class="row">
  			    			<form action="{!! url()->current() !!}" class="inline-form" id="data-filters-form" method="GET">

	  			    			@if(isset($data_filters) && $data_filters)
	  			    				@foreach($data_filters as $filter)
  			    						<div class="col-md-6">
		  			    					<div class="form-group">
		  			    						<label for="{{ $filter['name'] }}" >{{ $filter['label'] }}</label>
		  			    						@php 
    		  			    						$filter_value = null;
    		  			    						if(request()->has('filters')){
        		  			    						$request_filter = array_search($filter['name'], array_column(request()->get('filters'), 'name'));
        		  			    						if($request_filter !== false){
        		  			    							$filter_value = request()->get('filters')[$request_filter]['value'];
        		  			    						}
    		  			    						}
		  			    						@endphp
		  			    						@switch($filter['type'])
		  			    							@case('text')
		  			    								<input type="text" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" class="form-control" value="{{ $filter_value }}">
		  			    							@break
		  			    							@case('select')
		  			    								<select name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" class="form-control">
		  			    									<option value="">Please Select</option>
		  			    									@foreach($filter['options'] as $option_value => $option_name)
		  			    										<option value="{{ $option_value }}"
		  			    										{{ $filter_value == $option_value ? 'selected' : null }}
		  			    										>{{ $option_name }}</option>
		  			    									@endforeach
		  			    								</select>
		  			    							@break
		  			    							@case('language')
		  			    								<select name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" class="form-control">
		  			    									@foreach($languages as $code => $name)
		  			    										<option value="{{ $code }}"
		  			    										{{ $filter_value == $code ? 'selected' : null }}
		  			    										>{{ $name }}</option>
		  			    									@endforeach
		  			    								</select>
		  			    							@break
		  			    							@case('datetime_picker_range')
		  			    								<div class='input-group date datetimepicker-simple'>
		  			    								    <input type='text' class="form-control" name="{{ $filter['name'] }}" value="{{ $filter_value }}" id="{{ $filter['name'] }}" />
		  			    								    <span class="input-group-addon">
		  			    								        <span class="glyphicon glyphicon-calendar"></span>
		  			    								    </span>
		  			    								</div>
		  			    							@break
		  			    						@endswitch
		  			    					</div>
  			    						</div>
	  			    				@endforeach
	  			    			@endif
	  			    			<div class="col-md-12">
	  			    				<button type="sumbmit" name="submit_filters" id="submit_filters" class="btn btn-primary btn-flat btn-md" style="padding-left:30px; padding-right:30px ">
	  			    					Apply Filters
	  			    				</button>
	  			    			</div>
  			    			</form>
	    			        </div> <!-- /.col -->
	    			    </div> <!-- ./box-body -->
	    			</div>
	    			
		    		<div class="clearfix"></div>
		    		<hr>
		  </div>
		</div>
	</div>
</div>
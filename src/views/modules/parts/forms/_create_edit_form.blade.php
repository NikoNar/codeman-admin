
@if(isset($module)  && !isset($parent_lang_id))
	{!! Form::model($module, ['route' => ['modules.update', $module->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}
	{!! Form::hidden('id', $module->id) !!}
@elseif(isset($module) && isset($parent_lang_id) )
	{!! Form::model($module, ['route' => 'modules.store', 'enctype' => "multipart/form-data", 'method' => 'POST', 'id' => 'module-store']) !!}
	{!! Form::hidden('parent_lang_id', $parent_lang_id) !!}
@else
	{!! Form::open(['route' => 'modules.store', 'enctype' => "multipart/form-data", 'method' => 'POST']) !!}
@endif
<div class="col-md-9 border-right">

	<div class="col-md-12 no-padding">
		{!! Form::label('module_type', 'Module Type') !!}
		<div class="form-group">
			<div class='input-group'>
				<span class="input-group-addon">
				    <span class="far fa-file-alt"></span>
				</span>
				<select name="module_type"  class="select2 form-control module_type" >
					<option value="module" @if(isset($module->module_type) && $module->module_type == "module" )selected @endif>Module</option>
					<option value="template" @if(isset($module->module_type) && $module->module_type == "template" )selected @endif>Template</option>
				</select>
			</div>

			<div class="clearfix"></div>
			{{--			<a href="#" class="pull-right"  data-type="module"><i class="fa fa-plus"></i> Add New Option</a>--}}
		</div>
	</div>


	<div class="form-group">
		{!! Form::label('title', 'Title') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>
	</div>

	<div class="clearfix"></div>
	<br>



	<div class="form-group">
		{!! Form::label('icon', 'Font-Awsome Icon') !!}
		<div class='input-group'>
		    <span class="input-group-addon">
				@isset($module)
					{!! $module->icon !!}
				@else
		        <span class="fas fa-image"></span>
				@endif
		    </span>
			{!! Form::text('icon', null, ['class' => 'form-control']) !!}
		</div>
	</div>

	@if(!isset($module) || (isset($module) && $module->module_type != "template"))
		<div class="form-group hide-relations">
			{!! Form::label('relations[]', 'Relations') !!}
			<div class="form-group">
				<div class='input-group'>
					<span class="input-group-addon">
						<span class="fas fa-paperclip"></span>
					</span>
					<select name="relations[]" id="relations" class="select2 form-control" multiple>
						@if(isset($module) &&  null != $module_relations = json_decode($module->relations))
						@endif
						@foreach($relations as $id => $name)
								@if(isset($module) && $module->id == $id)
									@continue
								@endif
							<option value="{{$id}}" @if(isset($module_relations)&& in_array($id, $module_relations) )selected @endif>{{$name}}</option>
						@endforeach
					</select>
				</div>
				<div class="clearfix"></div>
				{{--			<a href="#" class="pull-right"  data-type="module"><i class="fa fa-plus"></i> Add New Option</a>--}}
			</div>
		</div>
		<div class="col-md-12 no-padding hide-options">
			{!! Form::label('options', 'Options') !!}
			<div class="form-group">
				<div class='input-group'>
					<span class="input-group-addon">
						<span class="fas fa-chevron-circle-down"></span>
					</span>
					@if(isset($module) &&  null != $module_options = json_decode($module->options))
						{{--					{{dd($module_options)}}--}}
						@if(!empty($module_options))
							@include('admin-panel::layouts.parts._options_dropdown', ['multiple' => 'multiple', 'selected' => $module_options])
						@else
							@include('admin-panel::layouts.parts._options_dropdown', ['multiple' => 'multiple'])
						@endif
					@else
						@include('admin-panel::layouts.parts._options_dropdown', ['multiple' => 'multiple'])
					@endif
				</div>

				<div class="clearfix"></div>
				{{--			<a href="#" class="pull-right"  data-type="module"><i class="fa fa-plus"></i> Add New Option</a>--}}
			</div>
		</div>
	@endif

	<input type="hidden" id="additional_options" name="additional_options">


	@isset($additional_options)
		<div class="panel-group" id="accordion">
		@foreach($additional_options as $id =>$arr)
			@if($arr['type'] != '')
			<div class="panel panel-default ">
				<div class="panel-heading"> <span class="glyphicon glyphicon-remove-circle pull-right "></span>
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$id}}">
							{{$arr['label']}}
						</a>
					</h4>
				</div>
				<div id="{{$id}}" class="panel-collapse collapse">
					<div class="panel-body">

						<div class="form-group row">
							<div class="col-md-3">
								<label for="">Input Type</label>
								<select name="type" class="input_type">
									<option value="" >Select type</option>
									<option value="text" @if($arr['type'] == 'text') selected @endif>Text</option>
									<option value="number" @if($arr['type'] == 'number') selected @endif>Number</option>
									<option value="textarea" @if($arr['type'] == 'textarea') selected @endif>Textarea</option>
									{{--								<option value="range">Range</option>--}}
									<option value="select" @if($arr['type'] == 'select') selected @endif>Select</option>
									<option value="checkbox" @if($arr['type'] == 'checkbox') selected @endif>Checkbox</option>
									<option value="radio" @if($arr['type'] == 'radio') selected @endif>Radio</option>
									<option value="image" @if($arr['type'] == 'image') selected @endif>Image</option>
									<option value="gallery" @if($arr['type'] == 'gallery') selected @endif>Gallery</option>
									<option value="editor" @if($arr['type'] == 'editor') selected @endif>Editor</option>
									<option value="color" @if($arr['type'] == 'color') selected @endif>Color</option>
								</select>
							</div>
						</div>




						<div class="form-group row">
							<div class="col-md-3 ">
								{!! Form::label('label', 'Label'); !!}
								{!! Form::text('label', $arr['label'], ['class' => 'form-control type_label']); !!}
							</div>
							<div class="col-md-3 ">
								{!! Form::label('name', 'Name'); !!}
								{!! Form::text('name', $arr['name'], ['class' => 'form-control type_name']); !!}
							</div>
							<div class="col-md-3">
								{!! Form::label('info', 'Info'); !!}
								{!! Form::text('info', $arr['info'], ['class' => 'form-control']); !!}
							</div>
							<div class="col-md-3" @if(!in_array($arr['type'], ['select', 'checkbox', 'radio'])) style="display:none" @endif>
								{!! Form::label('type_options', 'Options'); !!}
								{!! Form::text('type_options', $arr['type_options'], ['class' => 'form-control type_options', 'placeholder' => 'key:value, key:value, key:value ...', 'required']); !!}
							</div>
							<div class="col-md-3" @if(!in_array($arr['type'], ['select', 'checkbox', 'radio'])) style="display:none" @endif>
								{!! Form::label('multiple', 'Multiple'); !!}
								{!! Form::checkbox('multiple', null, isset($arr['multiple'])? $arr['multiple'] : false, ['class' => 'multiple']); !!}
							</div>
							<div class="col-md-3">
								{!! Form::label('required', 'Required'); !!}
								{!! Form::checkbox('required', null, isset($arr['required'])? $arr['required'] : false, ['class' => 'multiple']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('type', 'Type'); !!}
								{!! Form::hidden('type', $arr['type'], ['class' => 'form-control type']); !!}
							</div>
						</div>
					</div>
				</div>
			</div>
				@endif
			@endforeach
			<div class="panel panel-default template clone-hint" style="display:none">
				<div class="panel-heading"> <span class="glyphicon glyphicon-remove-circle pull-right "></span>

					<h4 class="panel-title">
						@php
							$id = time();
						@endphp
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$id}}">
							Additional option
						</a>
					</h4>

				</div>



				<div id="{{$id}}" class="panel-collapse collapse">
					<div class="panel-body">

						<div class="form-group row">
							<div class="col-md-3">
								<label for="">Input Type</label>
								<select name="type" class="input_type">
									<option value="" >Select type</option>
									<option value="text" >Text</option>
									<option value="number">Number</option>
									<option value="textarea">Textarea</option>
									{{--<option value="range">Range</option>--}}
									<option value="select">Select</option>
									<option value="checkbox">Checkbox</option>
									<option value="radio">Radio</option>
									<option value="image">Image</option>
									<option value="gallery">Gallery</option>
									<option value="editor">Editor</option>
									<option value="color">Color</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 ">
								{!! Form::label('label', 'Label'); !!}
								{!! Form::text('label', null, ['class' => 'form-control type_label']); !!}
							</div>
							<div class="col-md-3 ">
								{!! Form::label('name', 'Name'); !!}
								{!! Form::text('name', null, ['class' => 'form-control type_name']); !!}
							</div>
							<div class="col-md-3">
								{!! Form::label('info', 'Info'); !!}
								{!! Form::text('info', null, ['class' => 'form-control']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('type_options', 'Options'); !!}
								{!! Form::text('type_options', null, ['class' => 'form-control type_options', 'placeholder' => 'key:value, key:value, key:value ...', 'required']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('multiple', 'Multiple'); !!}
								{!! Form::checkbox('multiple', null, ['class' => 'form-control multiple']); !!}
							</div>
							<div class="col-md-3">
								{!! Form::label('required', 'Required'); !!}
								{!! Form::checkbox('required', null,  ['class' => 'multiple']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('type', 'Type'); !!}
								{!! Form::hidden('type', null, ['class' => 'form-control type']); !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="panel-group" id="accordion">

			<div class="panel panel-default template">
				<div class="panel-heading"> <span class="glyphicon glyphicon-remove-circle pull-right "></span>

					<h4 class="panel-title">
						@php
							$id = time();
						@endphp
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$id}}">
							Additional option
						</a>
					</h4>

				</div>



				<div id="{{$id}}" class="panel-collapse collapse">
					<div class="panel-body">

						<div class="form-group row">
							<div class="col-md-3">
								<label for="">Input Type</label>
								<select name="type" class="input_type">
									<option value="" >Select type</option>
									<option value="text" >Text</option>
									<option value="number">Number</option>
									<option value="textarea">Textarea</option>
	{{--								<option value="range">Range</option>--}}
									<option value="select">Select</option>
									<option value="checkbox">Checkbox</option>
									<option value="radio">Radio</option>
									<option value="image">Image</option>
									<option value="gallery">Gallery</option>
									<option value="editor">Editor</option>
									<option value="color">Color</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-3 ">
								{!! Form::label('label', 'Label'); !!}
								{!! Form::text('label', null, ['class' => 'form-control type_label']); !!}
							</div>
							<div class="col-md-3 ">
								{!! Form::label('name', 'Name'); !!}
								{!! Form::text('name', null, ['class' => 'form-control type_name']); !!}
							</div>
							<div class="col-md-3">
								{!! Form::label('info', 'Info'); !!}
								{!! Form::text('info', null, ['class' => 'form-control']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('type_options', 'Options'); !!}
								{!! Form::text('type_options', null, ['class' => 'form-control type_options', 'placeholder' => 'key:value, key:value, key:value ...','required']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('multiple', 'Multiple'); !!}
								{!! Form::checkbox('multiple', null, ['class' => 'form-control multiple']); !!}
							</div>
							<div class="col-md-3">
								{!! Form::label('required', 'Required'); !!}
								{!! Form::checkbox('required', null, ['class' => 'multiple']); !!}
							</div>
							<div class="col-md-3 " style="display:none">
								{!! Form::label('type', 'Type'); !!}
								{!! Form::hidden('type', null, ['class' => 'form-control type']); !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
    <br />
{{--    <button class="btn btn-lg btn-primary btn-add-panel pull-right"> <i class="glyphicon glyphicon-plus"></i> Add new panel</button>--}}
    <a href="#" class="pull-right  btn-add-panel" ><i class="fa fa-plus"></i> Add New Option</a>

	<div class="clearfix"></div>
	<div class="box">
	    <div class="box-header with-border">
	        <h3 class="box-title">SEO Options</h3>
	    </div>
	    <div class="box-body">
	    	<div class="form-group">
	    		{!! Form::label('meta-title', 'Meta Title'); !!}
	    		{!! Form::text('meta-title', null, ['class' => 'form-control', 'placeholder' => '%title% | %sitename%']) !!}
	    	</div>
	    	<div class="form-group">
	    		{!! Form::label('meta-description', 'Meta Description'); !!}
	    		{!! Form::text('meta-description', null, ['class' => 'form-control']) !!}
	    	</div>
	    	<div class="form-group">
	    		{!! Form::label('meta-keywords', 'Meta Keywords'); !!}
	    		{!! Form::text('meta-keywords', null, ['class' => 'form-control', 'data-role' => "tagsinput" ]) !!}
	    	</div>
	    </div>
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		{!! Form::label('created_at', 'Published Date'); !!}
		<div class="clearfix"></div>
        <div class='input-group col-md-6 pull-left'>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        	{!! Form::text('published_date', null, ['class' => 'form-control', 'id' => 'datepicker']) !!}
        </div>
        <div class="input-group bootstrap-timepicker col-md-6 pull-left">
        	{!! Form::text('published_time', null, ['class' => 'form-control timepicker', 'id' => 'timepicker']) !!}
        	<div class="input-group-addon">
        		<i class="fa fa-clock-o"></i>
        	</div>
        </div>
		<div class="clearfix"></div>
	</div>

	<div class="form-group">
		{!! Form::label('status', 'Status'); !!}
		{!! Form::select('status', ['published' => 'Published', 'draft' => 'Draft'], null, ['class' => 'form-control select2']); !!}
	</div>


	<div class="">
		@if(isset($order) && !empty($order))
			<div class="form-group">
				{!! Form::label('order', 'Order'); !!}
				{!! Form::number('order', $order, ['class' => 'form-control']) !!}
			</div>
		@else
			<div class="form-group">
				{!! Form::label('order', 'Order'); !!}
				{!! Form::number('order', null, ['class' => 'form-control']) !!}
			</div>
		@endif
	</div>
	<div class="clearfix"></div>
	<hr>
	<div class="form-group">
		@if(isset($module))
			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@else
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
		@endif
	</div>
</div>


{!! Form::close() !!}

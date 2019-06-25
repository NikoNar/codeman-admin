{{--@if(isset($category))--}}
{{--	{!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'PUT', 'enctype' => "multipart/form-data"]) !!}--}}
{{--	{!! Form::hidden('id', $category->id) !!}--}}
{{--@else--}}
{{--	{!! Form::open(['route' => ['categories.store', $module], 'enctype' => "multipart/form-data"]) !!}--}}
{{--@endif--}}
{{--@if(isset($type))--}}
{{--	{!! Form::hidden('type', $type) !!}--}}
{{--@endif--}}
{{--<div class="col-md-12 no-padding">--}}
{{--	<div class="form-group col-md-6">--}}
{{--		{!! Form::label('title', 'Category Name'); !!}--}}
{{--		<div class='input-group'>--}}
{{--		    <span class="input-group-addon">--}}
{{--		        <span class="fa fa-font"></span>--}}
{{--		    </span>--}}
{{--			{!! Form::text('title', null, ['class' => 'form-control']) !!}--}}
{{--		</div>--}}
{{--	</div>--}}
{{--	<div class="form-group col-md-6">--}}
{{--		@include('admin-panel::components.languages')--}}
{{--	</div>--}}
{{--	<div class="clearfix"></div>--}}
{{--		<div class="col-md-6">--}}
{{--			{!! Form::label('parent_id', 'Parent Category'); !!}--}}
{{--			<div class="form-group">--}}
{{--				--}}{{-- {!! Form::select('parent_id', ['' => 'Please Select'] + $categories ,  null, ['class' => 'form-control select2']) !!} --}}
{{--				<select name="parent_id" id="parent_id" class="form-control select2">--}}
{{--					<option value="0">Choose Category</option>--}}
{{--					@if(isset($categories) && !empty($categories))--}}
{{--					    @foreach($categories as $cat)--}}
{{--					        <option value="{{ $cat->id }}" @if(isset($category) && $cat->id == $category->parent_id ) {!! 'selected' !!} @endif>--}}
{{--					            {{ $cat->title }}--}}
{{--					            @if(count($cat->catChilds))--}}
{{--					            	@if(isset($category))--}}
{{--						                @include('admin-panel::layouts.parts._category_child',--}}
{{--						                ['childs' => $cat->catChilds, 'level' => 1, 'category_id' => $category->parent_id ])--}}
{{--					            	@else--}}
{{--					            		@include('admin-panel::layouts.parts._category_child',--}}
{{--						                ['childs' => $cat->catChilds, 'level' => 1 ])--}}
{{--					            	@endif--}}
{{--					            @endif--}}
{{--					        </option>--}}
{{--					    @endforeach--}}
{{--					@endif--}}
{{--				</select>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--		<div class="col-md-6">--}}
{{--			@if(isset($order) && !empty($order))--}}
{{--				<div class="form-group">--}}
{{--					{!! Form::label('order', 'Order'); !!}--}}
{{--					{!! Form::number('order', $order, ['class' => 'form-control']) !!}--}}
{{--				</div>--}}
{{--			@else--}}
{{--				<div class="form-group">--}}
{{--					{!! Form::label('order', 'Order'); !!}--}}
{{--					{!! Form::number('order', null, ['class' => 'form-control']) !!}--}}
{{--				</div>--}}
{{--			@endif--}}
{{--		</div>--}}
{{--	<div class="clearfix"></div>--}}
{{--	<div class="col-md-6">--}}
{{--		<div class="form-group">--}}
{{--			{!! Form::label('content', 'Description'); !!}--}}
{{--			{!! Form::textarea('content', null, ['class' => 'form-control']); !!}--}}
{{--		</div>--}}
{{--	</div>--}}
{{--	<div class="col-md-6">--}}
{{--		@include('admin-panel::components.thumbnail')--}}
{{--	</div>--}}

{{--	<hr>--}}
{{--	<div class="form-group">--}}
{{--		@if(isset($page))--}}
{{--			{!! Form::submit('Update', ['class' => 'btn btn-success form-control btn-flat']); !!}--}}
{{--		@else--}}
{{--			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}--}}
{{--		@endif--}}
{{--	</div>--}}
{{--</div>--}}
{{--<div class="clearfix"></div>--}}

{{--{!! Form::close() !!}--}}



{!! Form::open(['route' => ['categories.store', $module], 'enctype' => "multipart/form-data", 'id'=>"store_category"]) !!}
@if(isset($type))
	{!! Form::hidden('type', $type) !!}
@endif
<div class="col-md-12 no-padding">
	<div class="form-group col-md-6">
		{!! Form::label('title', 'Category Name'); !!}
		<div class='input-group'>
		    <span class="input-group-addon">
		        <span class="fa fa-font"></span>
		    </span>
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group col-md-6">
		<div class="form-group">
			@if(isset($languages) && !empty($languages))
				{!! Form::label('language_id', 'Language'); !!}
				{!! Form::select('language_id', $languages, isset($language_id) ? $language_id : null, ['class' => 'form-control select2', 'data-resource' => isset($module)? $module : '']); !!}
			@endif
		</div>	</div>
	<div class="clearfix"></div>
	<div class="col-md-6">
		{!! Form::label('parent_id', 'Parent Category'); !!}
		<div class="form-group">
			{{-- {!! Form::select('parent_id', ['' => 'Please Select'] + $categories ,  null, ['class' => 'form-control select2']) !!} --}}
			<select name="parent_id" id="parent_id" class="form-control select2">
				<option value="0">Choose Category</option>
				@if(isset($categories) && !empty($categories))
					@foreach($categories as $cat)
						<option value="{{ $cat->id }}" @if(isset($category) && $cat->id == $category->parent_id ) {!! 'selected' !!} @endif>
							{{ $cat->title }}
							@if(count($cat->catChilds))
								@if(isset($category))
									@include('admin-panel::layouts.parts._category_child',
                                    ['childs' => $cat->catChilds, 'level' => 1, 'category_id' => $category->parent_id ])
								@else
									@include('admin-panel::layouts.parts._category_child',
                                    ['childs' => $cat->catChilds, 'level' => 1 ])
								@endif
							@endif
						</option>
					@endforeach
				@endif
			</select>
		</div>
	</div>
	<div class="col-md-6">
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
	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label('content', 'Description'); !!}
			{!! Form::textarea('content', null, ['class' => 'form-control']); !!}
		</div>
	</div>
	<div class="col-md-6">
		@include('admin-panel::components.thumbnail')
	</div>

	<hr>
	<div class="form-group">
			{!! Form::submit('Publish', ['class' => 'btn btn-success form-control btn-flat']); !!}
	</div>
</div>
<div class="clearfix"></div>

{!! Form::close() !!}


{{-- {!! dd(json_decode($category->description)) !!} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="box">

		<div class="box-header with-border">
			@if(!isset($category))
				<h3 class="box-title">Create New Category</h3>
			@else
				<input type="hidden"  id="category_id" value="{{$category->id}}">
				<h3 class="box-title">Edit Category</h3>
				<a href="{{ route('categories.create', $type) }}" class="btn btn-primary btn-flat pull-right ">Add New</a>
				{{--				@if(isset($parent_lang_id) || isset($category) && $category->lang == 'arm')--}}
				{{--					@if(isset($parent_lang_id))--}}
				{{--						<a href="{{ route('category.edit', [$parent_lang_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>--}}
				{{--					@else--}}
				{{--						<a href="{{ route('category.edit', $category->parent_lang_id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>--}}
				{{--					@endif--}}
				{{--				@else--}}
				{{--					<a href="{{ route('category.translate',$category->id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to Armenian</a>--}}
				{{--				@endif--}}

				{{--				<a href="{{ route('category.translate', [$category->id, $category->language_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate</a>--}}
			@endif
		</div>
		<div class="box-body">
			@isset($ajax)
				@include('admin-panel::category.parts.forms._create_edit_modal')
			@else
				@include('admin-panel::category.parts.forms._create_edit_form')
			@endif
		</div>
		<!-- /.box-body -->
	</div>
@endsection
@section('script')
	<!-- Select2 -->
	<script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.js') }}"></script>

	<script src="{{ asset('admin-panel/bower_components/ckeditor/ckeditor.js') }}"></script>
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{{--	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\CategoryRequest') !!}--}}
	<script>
		$('select').select2();



	</script>
	<!-- <script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script> -->
@endsection()
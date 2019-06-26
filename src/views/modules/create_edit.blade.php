{{-- {!! dd(json_decode($module->description)) !!} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-header with-border">
	    	@if(!isset($module))
	        	<h3 class="box-title">Create New Module</h3>
	        @else
				<input type="hidden"  id="module_id" value="{{$module->id}}">
				<h3 class="box-title">Edit Module</h3>
				<a href="{{ route('modules.create') }}" class="btn btn-primary btn-flat pull-right ">Add New</a>
{{--				@if(isset($parent_lang_id) || isset($module) && $module->lang == 'arm')--}}
{{--					@if(isset($parent_lang_id))--}}
{{--						<a href="{{ route('module.edit', [$parent_lang_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>--}}
{{--					@else--}}
{{--						<a href="{{ route('module.edit', $module->parent_lang_id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to English</a>--}}
{{--					@endif--}}
{{--				@else--}}
{{--					<a href="{{ route('module.translate',$module->id) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate to Armenian</a>--}}
{{--				@endif--}}

{{--				<a href="{{ route('module.translate', [$module->id, $module->language_id]) }}" class="btn btn-warning btn-flat pull-right margin-right-15"><i class="fa fa-edit"></i> Translate</a>--}}
			@endif
	    </div>
	    <div class="box-body">
	        @include('admin-panel::modules.parts.forms._create_edit_form')
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
	
	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\ModuleRequest') !!}
	<script>
        $('select').select2();
	  	$(function () {
			if($('#editor').length > 0){
				CKEDITOR.replace('editor');
			}
	  	})
	</script>
	<script>
		$('body').off('change', '.module_type').on('change', '.module_type', function(){
			if($(this).val() == "template"){
				$('.hide-relations').hide();
				$('.hide-options').hide();
			}else{
				$('.hide-relations').show();
				$('.hide-options').show();
			}
		});
		var $template = $(".template");

		$(".btn-add-panel").on("click", function (e) {
			e.preventDefault();
			if($('.clone-hint').length > 0){
				var type = $('.template').last().prev().find('.input_type').val();
				var label = $('.template').last().prev().find('.type_label').val();
			} else {
				var type = $('.template').last().find('.input_type').val();
				var label = $('.template').last().find('.type_label').val();
			}

			if(type == '' && label == ''){
				alert('please fill th form');
				return
			}

			if (!validateForm()){
				return
			};
			var $newPanel = $template.clone();
			$('.panel-collapse').each(function(){
				$(this).removeClass('in');
			})
			var id = Date.now();
			$newPanel.find(".collapse").removeClass("in");
			$newPanel.find(".accordion-toggle").attr("href", "#" + id).text('Additional option');
			$newPanel.find(".panel-collapse").attr("id", id).addClass('in');
			$("#accordion").append($newPanel.fadeIn());
			$newPanel.find('.select2-container').remove()
			$("select").select2();
			$newPanel.find('input').val('');

		});
		$('body').off('change', '.input_type').on('change', '.input_type', function(){

			if($(this).val() == "select"){
				$(this).closest('.panel-body').find('#multiple').parent().show().attr('disabled', false);
			} else {
				$(this).closest('.panel-body').find('#multiple').parent().hide().attr('disabled', 'disabled');
			}

			if(['select', 'checkbox', 'radio'].includes($(this).val()) ){
				$(this).closest('.panel-body').find('.type_options').parent().show().attr('disabled', false);
			} else {
				$(this).closest('.panel-body').find('.type_options').parent().hide().attr('disabled', 'disabled');
			}
			$(this).closest('.panel-body').find('.type').val($(this).val());

		});
		$('body').off('blur', '.type_label').on('blur', '.type_label', function(){
			$(this).closest('.panel-body').find('.type_name').val(toSnakeCase($(this).val()));
			$(this).closest('.panel-collapse').siblings('.panel-heading').find('.accordion-toggle').text($(this).val());

		})

		$(document).on('click', '.glyphicon-remove-circle', function () {
			$(this).parents('.panel').get(0).remove();
		});


		toSnakeCase = function(string) {
			var s;
			s = string.replace(/[^\w\s]/g, "");
			s = s.replace(/\s+/g, " ");
			return s.toLowerCase().split(' ').join('_');
		};


		function validateForm() {
			$('.type_label').css("border","1px solid");
			$('.input_type').css("border","1px solid");

			let type = $('.template').last().find('.input_type').val();
			let label = $('.template').last().find('.type_label').val();
			let type_opts = $('.template').last().find('.type_options').val();
			if (type == "" && label != "") {
				$('.template').last().find('.select2-selection').css("border","1px solid red");
				setTimeout(function() {
					alert("Please select input type");
				}, 100);
				return false;
			}
			if (label == "" && type != "") {
				$('.template').last().find('.type_label').css("border","1px solid red");
				setTimeout(function() {
					alert("Please select label for input");
				}, 100);
				return false;
			}

			if(['select', 'checkbox', 'radio'].includes(type) && type_opts == "" ){
				$('.template').last().find('.type_options').css("border","1px solid red");
				setTimeout(function() {
					alert("Please fill options for input");
				}, 100);
				return false;
			}
			return true;
		}

		$('form').one('submit', function(e){
			e.preventDefault();
			var additional_opts = {};
			$('.panel-body').each(function(){
				additional_opts[$(this).parent().attr('id')] = $(this).find('input').serialize();
			});
			$('#additional_options').val(JSON.stringify(additional_opts));
			if (!validateForm()){
				return
			};
			$(this).submit();
		})
	</script>
	  <!-- <script src="{{ asset('admin-panel/content-builder/content-builder.js') }}"></script> -->
@endsection()
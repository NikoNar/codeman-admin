{{-- {!! dd(json_decode($module->description)) !!} --}}
@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/fontawesome-iconpicker-1.4.0/dist/css/fontawesome-iconpicker.css') }}">
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

	<!-- Icon-Picker -->
	<script type="text/javascript" src="{{ asset('admin-panel/plugins/fontawesome-iconpicker-1.4.0/dist/js/fontawesome-iconpicker.js')}}"></script>

	{!! JsValidator::formRequest('Codeman\Admin\Http\Requests\ModuleRequest') !!}
	<script>
        $('select').select2();
		$('.icp').iconpicker({

			// Popover title (optional) only if specified in the template
			title: false,

			// use this value as the current item and ignore the original
			selected: false,

			// use this value as the current item if input or element value is empty
			defaultValue: false,

			// (has some issues with auto and CSS). auto, top, bottom, left, right
			placement: 'bottom',

			// If true, the popover will be repositioned to another position when collapses with the window borders
			collision: 'none',

			// fade in/out on show/hide ?
			animation: true,

			// hide iconpicker automatically when a value is picked.
			// it is ignored if mustAccept is not false and the accept button is visible
			hideOnSelect: false,

			// show footer
			showFooter: false,

			// If true, the search will be added to the footer instead of the title
			searchInFooter: true,

			// only applicable when there's an iconpicker-btn-accept button in the popover footer
			mustAccept: false,

			// Appends this class when to the selected item
			selectedCustomClass: 'bg-primary',

			// // list of icon classes
			// icons: [],

			fullClassFormatter: function(val) {
				return 'fa ' + val;
			},

			// children input selector
			input: 'input,.iconpicker-input',

			// use the input as a search box too?
			inputSearch: true,

			// Appends the popover to a specific element.
			// If not set, the selected element or element parent is used
			container: false,

			// children component jQuery selector or object, relative to the container element
			component: '.input-group-addon,.iconpicker-component',

			// Plugin templates:
			templates: {
				popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
						'<div class="popover-title"></div><div class="popover-content"></div></div>',
				footer: '<div class="popover-footer"></div>',
				buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
						' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
				search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
				iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
				iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>',
			}

		});

	  	$(function () {
			if($('#editor').length > 0){
				CKEDITOR.replace('editor');
			}
	  	})
	</script>
	<script>
		$('body').off('change', '.module_type').on('change', '.module_type', function(){
			if($(this).val() == "template"){
				// $('.hide-relations').hide();
				$('.hide-options').hide();
			}else{
				// $('.hide-relations').show();
				$('.hide-options').show();
			}
		});
		var $template = $(".template");

		$(".btn-add-panel").on("click", function (e) {
			e.preventDefault();
			if($('.clone-hint').length > 0){
				if($('.clone-hint').length > 1){
					alert('exo');
					var type = $('.clone-hint').last().find('.input_type').val();
					var label = $('.clone-hint').last().find('.type_label').val();
				} else{
					var type = $('.template').last().prev().find('.input_type').val();
					var label = $('.template').last().prev().find('.type_label').val();
				}
			} else {
				var type = $('.template').last().find('.input_type').val();
				var label = $('.template').last().find('.type_label').val();
			}

			if(type == '' && label == ''){
				alert('please fill the form');
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
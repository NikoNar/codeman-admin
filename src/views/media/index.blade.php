@extends('admin-panel::layouts.app')

@section('style')
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/dropzone/dropzone.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
@endsection
@section('content')
{{-- {!! dd($images) !!} --}}
<div class="box">
    <div class="box-body">
    	<div class="col-md-12 no-padding">
    		<div class="view-mode" >
    			<a href="?view-mode=list"><i class="fa fa-th-list" aria-hidden="true"></i></a>
    			<a href="?view-mode=grid" class="active"><i class="fa fa-th-large" aria-hidden="true"></i></a>
    		</div>
    		<a href="javascript:void(0)" class="btn btn-primary btn-flat pull-left upload-image-dropzone">Upload Image</a>
    		<div class="form-group pull-left col-md-2">
    			@if(isset($dates) && !empty($dates))
					<select name="filter-by-year" id="filter-by-year" class="form-control pull-left">
						<option value="">All Dates</option>
						@foreach( $dates as $date)
							<option value="{{$date->month}},{{$date->year}}" @if(isset($selected_year) && $selected_year == $date->month.','.$date->year) selected @endif> {!! date('F', mktime(0, 0, 0, $date->month, 10)) !!} {{$date->year}}</option>
						@endforeach
					</select>
				@endif
    		</div>
    		<div class="form-group pull-right col-md-3">
    			@if(isset($dates) && !empty($dates))
    				<div class="input-group">
						<input type="text" name="media-search" id="media-search" placeholder="Search By Name" class="form-control">
						<div class="input-group-addon">
			        		<i class="fa fa-search"></i>
			        	</div>
    				</div>
				@endif
    		</div>
    	</div>
    	<div class="clearfix"></div>
    	<div class="upload_section" style="display: none;">
    		<hr class="no-margin-top">
			@include('admin-panel::media.parts.forms._upload_images_form')
    	</div>
    	<hr class="no-margin-top">
    	<div class="col-md-12 media-container" style="max-width: 1050px; margin: 0 auto; display: block; float: none;">
    		@if(request()->has('view-mode') && request()->get('view-mode') == 'list')
				<table class="table">
					<thead>
						<tr>
							<th><input type="checkbox" onClick="checkAll(this)" name="checked"></th>
							<th>Image</th>
							<th>Name</th>
							<th>File Size</th>
							<th>File Type</th>
							<th>Uploaded Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							@include('admin-panel::media.parts._media_list')
						</tr>
					</tbody>
				</table>
			@else
				@include('admin-panel::media.parts._media_list')
			@endif
    	</div>
    </div>
</div>
{!! Form::hidden('csrf-token', csrf_token(), ['id' => 'csrf-token']) !!}
<!-- Modal -->
<div id="file-info-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <div class="col-md-8 border-right">
        	<img src="" alt="" class="thumbnail full-image img-responsive">
        </div>
        <div class="col-md-4">
        	{{-- <p><b>File name:</b><span></span></p> --}}
        	<p><b>File type: </b><span id="file_type"></span></p>
        	<p><b>Uploaded on: </b><span id="created_at"></span></p>
        	<p><b>File size: </b><span id="file_size"></span></p>
        	<p><b>Dimensions: </b><span id="dimensions"></span></p>
        	<hr>
            <form id="media-info">
                {!! Form::hidden('_token', csrf_token(), ['id' => 'csrf-token']) !!}
                {!! Form::hidden('_method', 'PUT') !!}
                <div class="form-group">
                    <label for="url"> URL </label>
                    <input type="text" name="url" id="url" class="form-control" value="" disabled readonly>
                </div>
                <div class="form-group">
                    <label for="title"> File Name </label>
                    <input type="text" name="original_name" id="original_name" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="alt"> Alt Text </label>
                    <input type="text" name="alt" id="alt" class="form-control" value="">
                </div>
                <input type="hidden" name="file-id" id="data-id" >
            </form>
            {{-- <button type="button" class="btn btn-danger delete-file" name="remove-file"><i class="fa fa-trash"></i> Delete File </button> --}}
        	
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-primary" id="update-media" style="color: #fff">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
	<script src="{{ asset('admin-panel/plugins/dropzone/dropzone.js') }}"></script>
	<script src="{{ asset('admin-panel/js/dropzone-helper.js') }}"></script>
    <script src="{{ asset('admin-panel/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

	<script>
		jQuery(document).ready(function(){
        	$('select').select2();
		});
	</script>
@endsection

@extends('admin-panel::layouts.app')
@section('style')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/bootstrap-tagsinput-master/src/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
	<div class="box">
	    <div class="box-header with-border">
	        	<h3 class="box-title">Create New Feed</h3>
	    </div>
	    <div class="box-body">
	        	{!! Form::open(['route' => 'easy-feed-store', 'enctype' => "multipart/form-data"]) !!}

				<div class="col-md-9 border-right">
					<div class="form-group">
						{!! Form::label('brand_name', 'Name'); !!}
						<div class='input-group'>
						    <span class="input-group-addon">
						        <span class="fa fa-font"></span>
						    </span>
							{!! Form::text('brand_name', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('url', 'Url'); !!}
						<div class='input-group'>
						    <span class="input-group-addon">
						        <span class="fa fa-link"></span>
						    </span>
							{!! Form::text('url', null, ['class' => 'form-control']) !!}
						</div>
					</div>
					
					<div class="clearfix"></div>
					<br>	
					<div class="form-group">					
							{!! Form::submit('Save', ['class' => 'btn btn-success form-control btn-flat']); !!}						
					</div>
							
				{!! Form::close() !!}
				</div>					
	    </div>
	    <!-- /.box-body -->
	</div>
@endsection
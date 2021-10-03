@extends('admin.layouts.app')

@section('style')
@endsection

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Create New Menu</h3>
        		</div>
        		<div class="box-body" style="">
        			{!! Form::open(['action' => 'Admin\MenusController@store', 'method' => 'POST']) !!}
            			<div class="form-group">
							{!! Form::label('name', 'Menu Name') !!}
							{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
            			</div>
            			<div class="form-group">
							{!! Form::label('lang', 'Language') !!}
							{!! Form::select('lang', ['en' => 'English'], null, ['class' => 'form-control']) !!}
							<small class="form-text text-muted">By default language will be English. You can translate this menu in future.</small>
            			</div>
            			<div class="form-group">
							{!! Form::submit('Save', ['class' => 'btn btn-primary btn-flat form-control']) !!}
            			</div>
        			{!! Form::close() !!}
        		</div>
        		<!-- /.box-body -->
      		</div>
          	<!-- /. box -->
		</div>
	</div>
</section>
@endsection

@section('script')
	<!-- Laravel Javascript Validation -->
	<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

	{!! JsValidator::formRequest('App\Http\Requests\Admin\MenuRequest') !!}
@endsection

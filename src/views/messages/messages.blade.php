@if (isset($errors) && !empty($errors->all()))
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p class="text-xs-center"></i> <strong> Error !</strong> {!! $error !!}</p>
        @endforeach
    </div>
@endif
@if (Session::has('success'))
	<div class="alert alert-success">
		<p><i class="fa fa-check-circle"></i>
		<strong> Success ! </strong> {!! Session::get('success') !!}</p>
	</div>
@endif
@if (isset($success))
	<div class="alert alert-success">
		<p><i class="fa fa-check-circle"></i>
		<strong> Success ! </strong> {!! $success !!}</p>
	</div>
@endif
@if (Session::has('warning'))
	<div class="alert alert-warning">
		<p><i class="fa fa-exclamation-triangle"></i>
		<strong> Warning ! </strong> {!! Session::get('warning') !!}</p>
	</div>
@endif
@if (Session::has('error'))
	<div class="alert alert-danger">
		<p><i class="fa fa-times-circle"></i>
	 <strong> Error !</strong> {!! Session::get('error') !!}</p>
	</div>
@endif
{{-- 
094 710010 --}}
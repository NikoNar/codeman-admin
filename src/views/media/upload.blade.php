@extends('admin-panel::layouts.app')

@section('style')
	<link rel="stylesheet" href="{{ asset('admin-panel/plugins/dropzone/dropzone.css') }}">
@endsection
{{-- {!! dd($images) !!} --}}
@section('content')
<section class="content">
	@include('admin-panel::media.parts.forms._upload_images_form')
</section>
@endsection

@section('script')
	<script src="{{ asset('admin-panel/plugins/dropzone/dropzone.js') }}"></script>
	<script src="{{ asset('admin-panel/js/dropzone-helper.js') }}"></script>
@endsection




<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{env("APP_NAME")}}|Admin Panel</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/Ionicons/css/ionicons.min.css') }}">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/select2/dist/css/select2.min.css') }}">

  @yield('style')
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin-panel/dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('admin-panel/dist/css/skins/_all-skins.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('admin-panel/plugins/pace/pace.min.css') }}">

  <link rel="stylesheet" href="{{ asset('admin-panel/content-builder/content-builder.css') }}">
  
  <link rel="stylesheet" href="{{ asset('admin-panel/css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.css') }}">


  <script> app = {ajax_url : '{!! url('/') !!}'}</script>


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  @yield('after-style')

</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition skin-blue  sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">

  @include('admin-panel::layouts.parts._header')
  <header class="main-header">
	<!-- Logo -->
	<div class="logo">
	  <!-- mini logo for sidebar mini 50x50 pixels -->
	  <span class="logo-mini">
	  	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" width="3.26424in" height="0.400587in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd; width: 190px; height: 50px; margin-left: -70px;" viewBox="0 0 3649.24 447.83">
	  	 <defs>
	  	  <style type="text/css">
	  	   <![CDATA[
	  	    .fil0 {fill:#fff;fill-rule:nonzero}
	  	   ]]>
	  	  </style>
	  	 </defs>
	  	 <g id="Layer_x0020_1">
	  	  <polygon class="fil0" points="1671.84,242.99 1671.84,197.4 1722.59,197.4 1901.44,197.4 1901.44,242.99 1722.59,242.99 "></polygon>
	  	 <polygon class="fil0" points="1971.44,440.61 1671.84,440.61 1671.84,394.71 1772.59,394.71 1879.16,394.71 1901.44,394.71 "></polygon>
	  	  
	  	  <polygon class="fil0" points="1671.84,52.91 1671.84,7.32 1722.59,7.32 1971.44,7.32 1901.44,52.91 1722.59,52.91 "></polygon>
	  	 </g>
	  	</svg>
	  </span>
	  <!-- logo for regular state and mobile devices -->
	  <span class="logo-lg">
		{{ env('APP_NAME') }}
	  </span>
	</div>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
	  <!-- Sidebar toggle button-->
	  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
		<span class="sr-only">Toggle navigation</span>
		<i class="fa fa-ellipsis-v" style="font-size: 16px;"></i>
	  </a>
	  <ul class="nav navbar-nav ">
		<li><a href="{{ url('/') }}"  target="_blank" > <i class="fa fa-home"></i> Visit a website</a></li>
	  </ul>
	  <div class="navbar-custom-menu">
		<ul class="nav navbar-nav">
			
		  <!-- User Account: style can be found in dropdown.less -->
		  @if(Auth::check())
		  <li class="dropdown user user-menu">

			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<img src="{{ url('images/users/'.auth()->user()->profile_pic) }}" class="user-image" alt="User Image">
			    <span class="hidden-xs">{!! auth()->user()->name!!}</span>
			</a>
			<ul class="dropdown-menu">
			  <!-- User image -->
			  <li class="user-header">
				<img src="{{ url('images/users/'.auth()->user()->profile_pic) }}" class="user-image" alt="User Image">
				<p>
				 {!! auth()->user()->name !!}
				 <small>{!! auth()->user()->role !!}</small>
				  <small>Member since {{ date('M. Y', strtotime(auth()->user()->created_at)) }} </small>
				</p>
			  </li>
			  
			  <!-- Menu Footer-->
			  <li class="user-footer">
				<div class="pull-left">
				  <a href="#" class="btn btn-default btn-flat">Profile</a>
				</div>
				<div class="pull-right">
				  <a href="{{ url('/admin/logout') }}" class="btn btn-default btn-flat"><i class="fa fa-power-off"></i> logout</a>
				</div>
			  </li>
			</ul>
		  </li>
		@endif  
		</ul>
	  </div>
	</nav>
  </header>
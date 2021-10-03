@if(auth()->check() && (auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
<div class="admin-topnav" id="myadmin-topnav">
    <a href="{!! route('dashboard') !!}" class="admin-topnav-logo">
        <img src="{{ asset('admin-panel/login/images/codeman-logo-white.svg') }}" alt="CODEMAN Logo" width="100">
    </a>
    <a href="{!! route('dashboard') !!}" >{!! __('Dashboard') !!}</a>
    <div class="adt-dropdown">
        <button class="dropbtn">
            {!! __('Create') !!}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
              <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659l4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
            </svg>
        </button>
        <div class="adt-dropdown-content">
            <a href="{{ route('page-create') }}">Page</a>
            @if(env('is_shop'))
            <a href="{{ route('products.create') }}">Product</a>
            @endif
            @if(isset($admin_modules) && count($admin_modules) > 0)
                @foreach($admin_modules as $module)
                    <a href="{!! url("admin/resource/$module->slug/create") !!}"> {{ $module->title }} </a>
                @endforeach
            @endif
        </div>
    </div>
    @if(isset($page))
        <a href="{!! route('page-edit', $page->id) !!}">
            Edit {!! class_basename($page) !!}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
        </a>
    @endif
    
    @if(isset($resource) && get_class($resource) == 'Codeman\Admin\Models\Resource')
        <a href="{!! route('resources.edit', [$resource->type, $resource->id]) !!}">
            Edit {!! class_basename($resource) !!}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
        </a>
    @endif
    
    @if(isset($product) && get_class($product) == 'Codeman\Admin\Models\Shop\Product')
        <a href="{!! route('products.edit', [$product->id]) !!}">
            Edit {!! class_basename($product) !!}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
        </a>
    @endif
    
    <div class="adt-dropdown adt-menu-right">
        <button class="dropbtn">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&amp;color=fff&amp;background=23baef" alt="{{ auth()->user()->name }}" class="adt-user-image">
            {{ auth()->user()->name }}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
              <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659l4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
            </svg>
        </button>
        <div class="adt-dropdown-content">
           <a href="{{ route('logout') }}"
              onclick="event.preventDefault();
                            document.getElementById('admin-logout-form').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                  <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2h.5a.5.5 0 0 1 .5.5V15h-1V2zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                </svg>
               {{ __('Logout') }}
           </a>

           <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none !important">
               @csrf
           </form>
        </div>
    </div>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
</div>


<style type="text/css">
    /* Add a black background color to the top navigation */
    .adt-user-image{
        width: 25px; height: 25px; border-radius: 50%; margin-right: 5px; margin-top: 0px; vertical-align: text-bottom;
    }
    .admin-topnav {
      background-color: #343a40;
      overflow: hidden;
      box-shadow: inset -1px -1px 1px 0px #ffffff;
      z-index: 999999;
    }
    .admin-topnav .admin-topnav-logo{
        position: relative;
    }
    .admin-topnav .admin-topnav-logo::before {
        content: '';
        width: 100%;
        height: 100%;
        border-right: 1px solid #ccc;
        position: absolute;
        right: 0;
        top:0; 
    }

    /* Style the links inside the navigation bar */
    .admin-topnav a {
      float: left;
      display: block;
      color: #f2f2f2;
      text-align: center;
      padding: 12px 16px;
      text-decoration: none;
      font-size: 16px;
    }

    /* Add an active class to highlight the current page */
    .admin-topnav .active {
        background-color: #01a0de;
        color: white;
        box-shadow: inset 0px -5px 0px 0px #006b94;
    }

    /* Hide the link that should open and close the admin-topnav on small screens */
    .admin-topnav .icon {
      display: none;
    }

    /* adt-dropdown container - needed to position the adt-dropdown content */
    .admin-topnav .adt-dropdown {
      float: left;
      overflow: hidden;
    }
    .admin-topnav .adt-menu-right{
        float: right;
    }

    /* Style the adt-dropdown button to fit inside the admin-topnav */
    .admin-topnav .adt-dropdown .dropbtn {
      font-size: 16px;
      border: none;
      outline: none;
      color: white;
      padding: 12px 16px;
      background-color: inherit;
      font-family: inherit;
      margin: 0;
    }

    /* Style the adt-dropdown content (hidden by default) */
    .admin-topnav .adt-dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 999999999999;
    }

    /* Style the links inside the adt-dropdown */
    .admin-topnav .adt-dropdown-content a {
      float: none;
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }

    /* Add a dark background on admin-topnav links and the adt-dropdown button on hover */
    .admin-topnav a:hover, .admin-topnav .adt-dropdown:hover .dropbtn {
      background-color: #01a0de;
      color: white;
      box-shadow: inset 0px -5px 0px 0px #006b94;
    }

    /* Add a grey background to adt-dropdown links on hover */
    .admin-topnav .adt-dropdown-content a:hover {
      background-color: #ddd;
      color: black;
    }

    /* Show the adt-dropdown menu when the user moves the mouse over the adt-dropdown button */
    .admin-topnav .adt-dropdown:hover .adt-dropdown-content {
      display: block;
    }

    /* When the screen is less than 600 pixels wide, hide all links, except for the first one ("Home"). Show the link that contains should open and close the admin-topnav (.icon) */
    @media screen and (max-width: 600px) {
        .admin-topnav a:not(:first-child), .admin-topnav .adt-dropdown .dropbtn {
            display: none;
        }
        .admin-topnav a.icon {
            float: right;
            display: block;
        }
    }

    /* The "responsive" class is added to the admin-topnav with JavaScript when the user clicks on the icon. This class makes the admin-topnav look good on small screens (display the links vertically instead of horizontally) */
    @media screen and (max-width: 600px) {
      .admin-topnav.responsive {position: relative;}
      .admin-topnav.responsive a.icon {
        position: absolute;
        right: 0;
        top: 0;
      }
      .admin-topnav.responsive a {
        float: none;
        display: block;
        text-align: left;
      }
      .admin-topnav.responsive .adt-dropdown {float: none;}
      .admin-topnav.responsive .adt-dropdown-content {position: relative;}
      .admin-topnav.responsive .adt-dropdown .dropbtn {
        display: block;
        width: 100%;
        text-align: left;
      }
    }
</style>

<script>
    /* Toggle between adding and removing the "responsive" class to admin-topnav when the user clicks on the icon */
    function myFunction() {
      var x = document.getElementById("myadmin-topnav");
      if (x.className === "admin-topnav") {
        x.className += " responsive";
      } else {
        x.className = "admin-topnav";
      }
    }
</script>

@endif
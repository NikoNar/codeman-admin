
@if(!$item->hasRole('SuperAdmin') || auth()->user()->hasRole('SuperAdmin'))
	<tr data-id="{{ $item->id }}">
		<td><input type="checkbox" name="checked" value="{{ $item->id }}"></td>
		<td>
			<a href="javascript:void(0)" class="featured-img-change" style="width: 100px">
				<img src="{!! url('images/users/'.$item->profile_pic) !!}" class="thumbnail img-xs " width="100">

				<input name="thumbnail" type="hidden" value="">

			</a>

			{{ $item->full_name }}</td>

		<td>{{ $item->email }}</td>

		<td>
			@foreach($item->roles as $role)
				{{ $role->title.' ' }}
			@endforeach
		</td>
		<td class="text_capitalize">{{ date('M d Y H:i A', strtotime($item->email_verified_at)) }}</td>
		<td>{{ date('M d Y', strtotime($item->created_at)) }}</td>
		<td class="action">
			<div class="btn-group">
            	<button type="button" class="btn btn-info btn-flat">Action</button>
            	<button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                <span class="caret"></span>
	                <span class="sr-only">Toggle Dropdown</span>
              	</button>
              	<ul class="dropdown-menu" role="menu">
	                <li>
            			<a href="{!! route('admin.user.login', $item->id) !!}"  class="" title="Login to Account">
            				<i class="fa fa-sign-in-alt"></i> Login
            	    	</a>
	                </li>
	                <li>
	                	<a href="{{ route('admin.user.profile', $item->id ) }}" title="Profile" class="">
	                		<i class="fa fa-user"></i> Profile
	                	</a>
	                </li>
	                <li>
	                	<a href="{{ route('user.edit', $item->id ) }}" title="Edit" class="">
	                		<i class="fa fa-edit"></i> Edit
	                	</a>
	                </li>
	                <li class="divider"></li>
	                <li>
	                	<a href="{{ route('user.destroy', $item->id ) }}" title="Delete" class="">
	                		<i class="fa fa-trash"></i> Delete
	                	</a>
	                </li>
              	</ul>
            </div>
		</td>
	</tr>
@endif


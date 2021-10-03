{{-- @extends('admin-panel::layouts.app')

@section('style')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('admin-panel/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
	<div class="box">
	    <div class="box-body">
	    	<div class="col-md-6 no-padding">
	    		<a href="{{ route('menu-create') }}" class="btn btn-primary btn-flat pull-left ">Create New Menu</a>
	    	</div>
	    	<table id="example1" class="table table-bordered table-striped">
	       		<thead>
	       			<tr>
						<th width="5%">#</th>
						<th>Name</th>
						<th width="15%">Language</th>
						<th width="15%">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($menus as $key => $menu)
						<tr>
							<td>{{ ++ $key }}</td>
							<td>{{ $menu->name }}</td>
							<td>{{ $menu->lang }}</td>
							<td class="action">
								<a href="{{ route('menu-show', $menu->id ) }}" title="Show" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i></a>
								<a href="{{ route('menu-translate', $menu->id ) }}" title="Translate Menu" class="btn btn-xs btn-primary"><i class="fa fa-language"></i></a>
								<a href="{{ route('menu-destroy', $menu->id ) }}" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
	    </div>
	    <!-- /.box-footer-->
	</div>
@endsection


@section('script')
<!-- DataTables -->
<script src="{{ asset('admin-panel/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-panel/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<script>
	$('#example1').DataTable({
	  'paging'      : true,
	  'lengthChange': false,
	  'searching'   : true,
	  'ordering'    : true,
	  'info'        : true,
	  'autoWidth'   : false
	})
</script>
@endsection --}}
@extends('admin-panel::layouts.app')

@section('content')
    {!! Menu::render() !!}
@endsection

@section('script')
    {!! Menu::scripts() !!}
@endsection
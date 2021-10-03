<div class="form-group">
	{!! Form::label('status', 'Status') !!}
    {!! Form::select('status', 
    isset($status_options) ? $status_options : 
    [
		'published' => 'Published',
        'draft' => 'Draft',
        'pending' => 'Pending',
        'archive' => 'Archive',
        'deleted' => 'Deleted',
        'schedule' => 'Schedule',
	], null, ['class' => 'form-control select2']) !!}
</div>
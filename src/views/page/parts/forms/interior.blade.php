<div class="form-group">
	{!! Form::label('meta[section-1][date]', 'Start Date'); !!}
	{!! Form::text('meta[section-1][date]', null, ['class' => 'form-control']); !!}	
</div>
<div class="form-group">
	{!! Form::label('meta[section-1][title]', 'Title'); !!}
	{!! Form::text('meta[section-1][title]', null, ['class' => 'form-control']); !!}	
</div>
<div class="form-group">
	{!! Form::label('meta[section-1][content]', 'Content'); !!}
	{!! Form::textarea('meta[section-1][content]', null, ['class' => 'form-control', 'id' => 'section-1']); !!}	
</div>

<div class="form-group">
	{!! Form::label('meta[section-2][title]', 'Title'); !!}
	{!! Form::text('meta[section-2][title]', null, ['class' => 'form-control']); !!}	
</div>
<div class="form-group">
	{!! Form::label('meta[section-2][content]', 'Content'); !!}
	{!! Form::textarea('meta[section-2][content]', null, ['class' => 'form-control', 'id' => 'section-2']); !!}	
</div>

<div class="form-group">
	{!! Form::label('meta[section-3][title]', 'Title'); !!}
	{!! Form::text('meta[section-3][title]', null, ['class' => 'form-control']); !!}	
</div>
<div class="form-group">
	{!! Form::label('meta[section-3][content]', 'Content'); !!}
	{!! Form::textarea('meta[section-3][content]', null, ['class' => 'form-control', 'id' => 'section-3']); !!}	
</div>

<div class="form-group">
	{!! Form::label('meta[section-4][title]', 'Title'); !!}
	{!! Form::text('meta[section-4][title]', null, ['class' => 'form-control']); !!}	
</div>
<div class="form-group">
	{!! Form::label('meta[section-4][content]', 'Content'); !!}
	{!! Form::textarea('meta[section-4][content]', null, ['class' => 'form-control', 'id' => 'section-4']); !!}	
</div>

<div class="form-group">
	{!! Form::label('meta[section-5][title]', 'Title'); !!}
	{!! Form::text('meta[section-5][title]', null, ['class' => 'form-control']); !!}	
</div>
<div class="form-group">
	{!! Form::label('meta[section-5][content]', 'Content'); !!}
	{!! Form::textarea('meta[section-5][content]', null, ['class' => 'form-control', 'id' => 'section-5']); !!}	
</div>
@isset($lecturers)

	    <div class="form-group">
			{{ Form::label('lecturers', 'Lecturers') }}
			<div class="input-group">
			    <span class="input-group-addon">
			        <span class="fa fa-user"></span>
			    </span>
				{{ Form::select('lecturers[]', $lecturers, isset($selected) && $selected != null ? $selected : null, ['id' => 'lecturers', 'multiple' => 'multiple']) }}
			</div>
	    </div>
@endif
@empty($lecturers)
    {{ __('First you need to add lecturers')}}
@endempty



<div class="faq-container">	
	@if(isset($page) && ($page->meta['faq'] != null ))
		@foreach($page->meta['faq'] as $key => $meta)
		<div class="faq-wrapper">
			<i class="text-danger fa fa-trash remove-faq pull-right"><span class="text-danger ml-3">  Delete</span></i>
			<div class="form-group ">
				{!! Form::label('meta[faq]['.$key.'][1]', 'Question'); !!}
				{!! Form::text('meta[faq]['.$key.'][1]', isset($meta) && isset($meta[1]) ? $meta[1] : null, ['class' => 'form-control question']); !!}	
			</div>
			<div class="form-group ">
				{!! Form::label('meta[faq]['.$key.'][2]', 'Answer'); !!}
				{!! Form::textarea('meta[faq]['.$key.'][2]', isset($meta) && isset($meta[2]) ? $meta[2] : null, ['class' => 'form-control answer', 'id' => '' ]); !!}	
			</div>			
		</div>
		@endforeach
	@else
	<div class="faq-wrapper">
			<i class="text-danger fa fa-trash remove-faq pull-right"><span class="text-danger ml-3">  Delete</span></i>
			<div class="form-group ">
				{!! Form::label('meta[faq][0][1]', 'Question'); !!}
				{!! Form::text('meta[faq][0][1]', isset($meta) && isset($meta[1]) ? $meta[1] : null, ['class' => 'form-control question']); !!}	
			</div>
			<div class="form-group ">
				{!! Form::label('meta[faq][0][2]', 'Answer'); !!}
				{!! Form::textarea('meta[faq][0][2]', isset($meta) && isset($meta[2]) ? $meta[2] : null, ['class' => 'form-control answer', 'id' => '' ]); !!}	
			</div>			
		</div>
		@endif
	<i class="fa fa-plus pull-right clone">Add Question</i>
</div> 
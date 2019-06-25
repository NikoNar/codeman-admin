<div class="form-group">
        @if(isset($languages) && !empty($languages))
                @isset($resource)
                        <input type="hidden" name="resource_id" value="{{$resource->id}}">
                @endif
                {!! Form::label('language_id', 'Language'); !!}
                {!! Form::select('language_id', $languages, isset($language_id) ? $language_id : null, ['class' => 'form-control select2 languages', 'data-resource' => isset($module)? $module : '']); !!}
        @endif
</div>
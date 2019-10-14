<div class="form-group">
        @if(isset($languages) && !empty($languages))
                @isset($resource)
                        <input type="hidden" name="resource_id" value="{{$resource->id}}">
                @endif
                {!! Form::label('lang', 'Language'); !!}
                {!! Form::select('lang', $languages, isset($lang) ? $lang : null, ['class' => 'form-control select2 languages', 'data-resource' => isset($module)? $module : '']); !!}
        @endif
</div>
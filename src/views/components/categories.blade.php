<div class="col-md-12 no-padding">
    @if(!isset($noLabel))
        {!! Form::label('categories_id', 'Categories') !!}
    @endif
    <div class="form-group">
        <div class='input-group'>
			<span class="input-group-addon">
			    <span class="glyphicon glyphicon-tag"></span>
			</span>
            @if(isset($render))
                @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple', 'selected' => $selected])
            @else
                @if(isset($resource) &&  null != $resource_categories = $resource->categories()->get()->pluck('id')->toArray())
                    @if(!empty($resource_categories))
                        @if(isset($translated_categories) && !empty($translated_categories))
                            @php
                            $resource_categories = $translated_categories
                            @endphp
                        @endif
                        @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple', 'selected' => $resource_categories])
                    @else
                        @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
                    @endif
                @else
                    @include('admin-panel::layouts.parts.categories_dropdown', ['multiple' => 'multiple'])
                @endif
            @endif
        </div>
        <div class="clearfix"></div>
        @if(!isset($noAddLink))
            <a href="#" class="pull-right" id="add-category" data-type="{{$module}}"><i class="fa fa-plus"></i> Add New Category</a>
        @endif
    </div>
</div>

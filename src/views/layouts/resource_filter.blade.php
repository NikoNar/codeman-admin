<form id="listing-filter">

    {{-- <input type="hidden" name="view_direction" id="viewDirection" value="/admin/products/" >
    <input type="hidden" name="collection_name" id="collection_name" value="resources" >
    <input type="hidden" name="modelName" id="modelName" value="\App\Models\{{Str::singular(ucwords($module))}}" >
    <input type="hidden" name="view_path" id="view_path" value="{{ 'admin.products' }}" > --}}
   
	{{-- <div class="col-md-3 pull-right no-padding" >
        <div class="input-group ">
    		<input type="text" name="search" id="resource-search" placeholder="Search By Title" class="form-control" value="{!! request()->has('search') ? request()->get('search') : '' !!}">
    		<div class="input-group-addon input-group-blue">
        		<i class="fa fa-search"></i>
        	</div>
        </div>
	</div>
    <div class="col-md-2 pull-right no-padding" style="margin-left: 15px;">
        <select name="search_by" id="search_by" class="form-control">
            <option value="title" {!! request()->has('search_by') && request()->get('search_by') == 'title' ? 'selected' : '' !!}>Search By Title</option>
        </select>
    </div> --}}
    <div class="col-md-3 pull-right no-padding" >
        <div class="input-group">
            <input type="text" name="search" id="resource-search" placeholder="Search" class="form-control" value="{!! request()->has('search') ? request()->get('search') : '' !!}">
            <input type="hidden" name="search_by" value="*">
            <div class="input-group-addon input-group-blue">
                <i class="fa fa-search"></i>
            </div>
        </div>
    </div>
   @if(isset($languages))
    	<div class="col-md-3 pull-right no-padding" style="width: 21%">
    		<div class="input-group">
    			<div class="input-group-addon input-group-blue">
            		<i class="fa fa-language"></i>
            	</div>
                @if(isset($languages) && !empty($languages))
                    {!! Form::select('language', $languages, isset($language) ? $language : null, ['class' => 'form-control select2 ', 'name'=>'language']); !!}
                @endif
    		</div>
    	</div>
    @endif()
{{--    @if(isset($categories) && !empty($categories))--}}
{{--        <div class="col-md-3 pull-right no-padding" style="margin-left: 15px;">--}}
{{--            <div class="input-group">--}}
{{--                <div class="input-group-addon input-group-blue">--}}
{{--                    <i class="fa fa-tags"></i>--}}
{{--                </div>--}}
{{--                @include('admin-panel::layouts.parts.categories_dropdown')--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--     @if(isset($brands) && !empty($brands))
        @include('admin-panel::feedproduct.parts.brands_dropdown')
    @endif --}}
	{{-- @if(isset($dates) && !empty($dates))
    	<div class="col-md-3 pull-right">
    		<div class="input-group">
    			<div class="input-group-addon input-group-blue">
            		<i class="fa fa-calendar"></i>
            	</div>

            	<select name="created_at" id="filter-by-year" class="form-control pull-left">
            		<option value="">All Dates</option>
            		@foreach( $dates as $date)
            			<option value="{{$date->month}},{{$date->year}}" @if(isset($selected_date) && $selected_date == $date->month.','.$date->year) selected @endif> {!! date('F', mktime(0, 0, 0, $date->month, 10)) !!} {{$date->year}}</option>
            		@endforeach
            	</select>
            </div>
    	</div>
	@endif --}}
    @if(isset($years) && !empty($years))
        <div class="col-md-3 pull-right no-padding" style="width: 25%">
            <div class="input-group">
                <div class="input-group-addon input-group-blue">
                    <i class="fa fa-calendar"></i>
                </div>

                <select name="created_at" id="filter-by-year" class="form-control pull-left">
                    <option value="">All Years</option>
                    @foreach( $years as $year)
                        <option value="{{$year}}" @if(isset($selected_year) && $selected_year == $year) selected @endif> {!! $year !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    <div class="col-md-3 pull-right no-padding" style="width: 18%">
        <div class="input-group">
            <input type="text" name="per-page" id="resource-perpage" placeholder="Show 10 Records" class="form-control" value="{{  request()->get('per-page') }}">
        </div>
    </div>
</form>

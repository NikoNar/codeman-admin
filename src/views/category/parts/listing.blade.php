<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort reorder"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Name</th>
			<th>Description</th>
{{--			<th>Type</th>--}}
			<th width="15%">Created Date</th>
			<th width="15%">Last Time Update</th>
			<th width="12%">Actions</th>
		</tr>
	</thead>
{{--	<tbody>--}}
{{--		@foreach($categories as $key => $category)--}}
{{--			@if(!$category->parent_id)--}}
{{--			@include('admin-panel::category.parts.item')--}}

{{--			@if(count($category->catChilds))--}}
{{--                @include('admin-panel::category.parts.childs-listing',['childs' => $category->catChilds, 'level' => 0])--}}
{{--            @endif--}}
{{--			@endif--}}
{{--		@endforeach--}}
{{--	</tbody>--}}
	<tbody>
	{{-- {!! dd($categories) !!} --}}
	{{-- @foreach($categories as $key => $category) --}}
	{{-- @include('admin-panel::category.parts.item') --}}
	{{-- @php $level = 0 @endphp --}}
	{{-- @if($category->slug == 'մաշկի-խնամք')
    {!! dd($category->catChilds) !!}
    @endif --}}
	{{-- @if(count($category->catChilds)) --}}
	@php
		function recurs($result, $parent_id, &$html = '')
        {
            if(isset($result[$parent_id])){

                foreach($result[$parent_id] as $key => $category) {
                    $html .='<tr data-id="'.$category->id.'" >';
                        $html .='<td><input type="checkbox" name="checked" value="'.$category->id.'"></td>';
                        $html .='<td>';
                            for($i = 2; $i <= $category->level; $i++):
                                $html .=' ----- ';
                            endfor;
                            $html .= ' <a href="javascript:void(0)" class="featured-img-change media-open">
			<img src="'.$category->thumbnail .'" class="thumbnail img-xs ">
			<i class="fa fa-camera"></i>
			<input name="thumbnail" type="hidden" value="">
		</a>'.$category->title.'</td>';
                        $html .='<td>'.$category->content.'</td>';
                        $html .='<td>'.date('m/d/Y g:i A', strtotime($category->created_at)).'</td>';
                        $html .='<td>'.date('m/d/Y g:i A', strtotime($category->updated_at)).'</td>';


                        $html .='<td class="action">';
                            $html .='<a href="'.route('categories.edit', [$category->id, $category->type]).'" title="Edit" data-id="'.$category->id.'" data-type="'.$category->type.'" class="btn btn-xs btn-warning edit-category"><i class="fa fa-edit"></i></a>';
                            $html .='<a href="'.route('categories.destroy', [$category->id]).'" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i></a>';
                        $html .='</td>';
                    $html .='</tr>';
                recurs($result, $category->id, $html);
                }
            }
            return $html;
        }
	@endphp
	{!! recurs($categories, 0) !!}
	{{-- @include('admin-panel::category.parts.childs-listing',['categories' => $categories, 'level' => 0, 'parent_id' => 0]) --}}
	{{-- @endif --}}
	{{-- @endforeach --}}
	</tbody>
</table>
<div class="clearfix"></div>
{{-- <div class="pull-right">{!! $categories->links() !!}</div>--}}
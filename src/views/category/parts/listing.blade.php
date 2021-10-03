<table id="sortable-table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th width="3%" class="no-sort reorder"><input type="checkbox" name="checked" onClick="checkAll(this)"></th>
			<th>Name</th>
			<th>Status</th>
			<th width="15%">Created Date</th>
			<th width="15%">Last Time Update</th>
			<th width="140px">Actions</th>
		</tr>
	</thead>
	<tbody>
	@php
		function recursCats($result, $parent_id, &$html = ''){
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
                        $html .='<td>'.$category->status.'</td>';
                        $html .='<td>'.date('m/d/Y g:i A', strtotime($category->created_at)).'</td>';
                        $html .='<td>'.date('m/d/Y g:i A', strtotime($category->updated_at)).'</td>';


                        $html .='<td class="action">';
                            $html .='<a href="'.route('categories.edit', [$category->id, $category->type]).'" title="Edit" data-id="'.$category->id.'" data-type="'.$category->type.'" class="btn btn-xs btn-warning edit-category"><i class="fa fa-edit"></i> Edit</a>';
                            $html .='<a href="'.route('categories.destroy', [$category->id]).'" title="Delete" class="btn btn-xs btn-danger confirm-del"><i class="fa fa-trash"></i> Remove</a>';
                        $html .='</td>';
                    $html .='</tr>';
					recursCats($result, $category->id, $html);
                }
            }
            return $html;
        }
	@endphp
	{!! recursCats($categories, 0) !!}
	</tbody>
</table>
<div class="clearfix"></div>
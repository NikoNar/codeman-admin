<!-- Modal -->
<div class="modal fade" id="category-add-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title pull-left" id="exampleModalLabel">@if(isset($category)) Category Edit @else Category Add @endif</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('admin-panel::category.parts.forms._create_edit_modal')
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
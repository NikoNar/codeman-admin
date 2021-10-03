<!-- Modal -->
<div class="modal fade" id="variations_modal" tabindex="-1" role="dialog" aria-labelledby="variations_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="variations_modal_label">Variations</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:0 15px !important">
                <input type="hidden" name="resource_id" value="{{ $resource_id }}">
                <div class="filters row" style="background-color: #f9f9f9; padding: 15px 0">
                    @include('admin-panel::shop.variations.components._modal_filters')
                </div>
                <div class="variations-list">
                    <hr>
                    <div class="clearfix"></div>
                    <div class="col-md-offset-10 col-md-2 no-padding-left no-padding-right">
                        <div class="form-group">
                            <select name="per-page" id="table-perpage" class="form-control w-100"
                             data-placeholder="Show Per Page">
                                <option value=""></option>
                                <option value="10" {{ request()->get('length') == '10' ? 'selected' : null }}>10</option>
                                <option value="25" {{ request()->get('length') == '25' ? 'selected' : null }}>25</option>
                                <option value="50" {{ request()->get('length') == '50' ? 'selected' : null }}>50</option>
                                <option value="100" {{ request()->get('length') == '100' ? 'selected' : null }}>100</option>
                                <option value="-1" {{ request()->get('length') == '-1' ? 'selected' : null }}>Show all</option>
                            </select>
                        </div>
                    </div>
                    @include('admin-panel::shop.variations.components._modal_variations_table')
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-8 alert_container">
                    
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="insterSelectedVariations">Insert Selected Item(s)</button>
                </div>
            </div>
        </div>
    </div>
</div>
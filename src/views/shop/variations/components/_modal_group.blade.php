<!-- Modal -->
<div class="modal fade groupModal-lg" id="groupModal-lg" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="groupModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="display table table-bordered table-striped table-responsive table-sortable actions-bar-fixed"> 
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Is Private</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($group as $g_val)
                    <tr>
                        <th>{{$g_val->name}}</th>
                        <th>{{($g_val->is_private) ? "Private" : "Public"}}</th>
                        <th><a href="http://www.copcopine.fx/admin/product-groups/select/{{$g_val->id}}" class="btn btn-sm btn-info btn-flat" style="width:80%">Select Product Group</a></th>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Is Private</th>
                    <th>Action</th>
                </tr>

            </tfoot>
        </table>
        {{-- {{dd($group)}} --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
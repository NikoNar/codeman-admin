<!-- Modal -->
<div class="modal fade" id="transaction-details" tabindex="-1" role="dialog" aria-labelledby="transaction-detailsModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transaction-detailsModalCenterTitle" style="display: inline-block;">{!! $title !!}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
        	<tbody>
        		@if(isset($transaction) && !empty($transaction))
		        	@foreach($transaction as $key => $value)
		        	<tr>
		        		<th>{!! $key !!}</th>
		        		<td>{!! $value !!}</td>
		        	</tr>
		        	@endforeach
	        	@endif
        	</tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>
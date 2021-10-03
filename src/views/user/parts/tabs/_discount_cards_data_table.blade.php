<div class="tab-pane"  id="discount_card">
    <div class="row col-md-12">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
            Attach Card To User
        </button>
    </div>
    <div class="clearfix"></div>
    <br>
    <table id="user_discount_cards" class="display table table-bordered table-striped" style="width:100%">
        <thead>
        <tr>
            <th>User</th>
            <th>Discount Card</th>
            <th>Card Code</th>
            <th>Admin ID</th>
            <th>Status</th>
            <th>Assigned Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        {{-- JS Datatable will load data here --}}
        </tbody>
        <tfoot>
        <tr>
            <th>User</th>
            <th>Discount Card</th>
            <th>Card Code</th>
            <th>Admin ID</th>
            <th>Status</th>
            <th>Assigned Date</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
</div>
@include('admin-panel::user.modals.modal_discount_card')

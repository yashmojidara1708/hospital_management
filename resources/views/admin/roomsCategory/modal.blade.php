<!-- Add Room Modal -->
<div class="modal fade" id="add_rooms_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Room Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="RoomsForm" name="RoomsForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hidden_room_id" name="hidden_room_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Room Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="room_name" name="room_name" class="form-control"
                                placeholder="Please enter Room name" required>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Room Modal -->

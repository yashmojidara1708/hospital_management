<!-- Add Room Modal -->
<div class="modal fade" id="add_rooms_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="RoomsForm" name="RoomsForm">
                    @csrf
                    <input type="hidden" id="hidden_room_id" name="hidden_room_id" value="">

                    <div class="form-group">
                        <label>Room Number</label>
                        <input type="text" id="room_number" name="room_number" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Beds</label>
                        <input type="number" id="beds" name="beds" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Charges</label>
                        <input type="number" id="charges" name="charges" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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

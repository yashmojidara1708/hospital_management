<!-- Add Modal -->
<div class="modal fade" id="Add_Rooms" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Rooms</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="roomForm" name="roomForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hid" name="hid" value="">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Category</label>
                        <div class="col-lg-9">
                            <select name="category" class="form-control" id="category">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Total Rooms</label>
                        <div class="col-lg-9">
                            <input type="number" id="total_rooms" name="total_rooms" class="form-control"
                                placeholder="Please enter Total number of Rooms ">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Beds</label>
                        <div class="col-lg-9">
                            <input type="number" id="beds_per_room" name="beds_per_room" class="form-control"
                                placeholder="Please enter Beds per room">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Charges</label>
                        <div class="col-lg-9">
                            <input type="number" id="charges" name="charges" class="form-control"
                                placeholder="Please enter Charges for per bed">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Status</label>
                        <div class="col-lg-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="active" value="1">
                                <label class="form-check-label" for="gender_male">
                                Active
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inactive" value="0">
                                <label class="form-check-label" for="gender_female">
                                Inactive
                                </label>
                            </div>
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
<!-- /ADD Modal -->
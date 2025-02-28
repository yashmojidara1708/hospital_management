<!-- Add Modal -->
<div class="modal fade" id="Add_Specialities_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Specialities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="specialitiesForm" name="specialitiesForm"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Specialities Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Please enter specialities name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Status</label>
                        <div class="col-lg-9">
                            <select class="select" id="status" name="status">
                                <option>Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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

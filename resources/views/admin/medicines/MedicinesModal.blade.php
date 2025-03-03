<!-- Add Modal -->
<div class="modal fade" id="Add_Medicines_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Medicine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="MedicinesForm" name="MedicinesForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hid" name="hid" value="">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Medicine Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Please enter medicine name">
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Expiry Date</label>
                        <div class="col-lg-9">
                            <input type="date" id="expiry_date" name="expiry_date" class="form-control"
                                placeholder="Please enter expiry date">
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Price</label>
                        <div class="col-lg-9">
                            <input type="text" id="price" name="price" class="form-control"
                                placeholder="Please enter price">
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Stock</label>
                        <div class="col-lg-9">
                            <input type="text" id="stock" name="stock" class="form-control"
                                placeholder="Please enter stock">
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

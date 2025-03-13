<!-- Add Modal -->
<div class="modal fade" id="Add_Patients_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Patients</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="PatientsForm" name="PatientsForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hid" name="hid" value="">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Patient Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Please enter Patient name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Age</label>
                        <div class="col-lg-9">
                            <input type="text" id="age" name="age" class="form-control"
                                placeholder="Please enter age">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Address</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control m-b-20" name="address" id="address"
                                placeholder="Please enter Address">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="country" class="form-control" id="country">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="city" class="form-control" id="city">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="state" class="form-control" id="state">
                                            <option value="">Select State</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="text" placeholder="ZIP code" class="form-control" name="zip"
                                            id="zip">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Phone</label>
                        <div class="col-lg-9">
                            <input type="text" id="phone" name="phone" class="form-control"
                                placeholder="Please enter Phone">
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Email</label>
                        <div class="col-lg-9">
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Please enter email">
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Last Visit</label>
                        <div class="col-lg-9">
                            <input type="date" id="last_visit" name="last_visit" class="form-control"
                                placeholder="Please enter Last Visit">
                        </div>
                    </div>
                <!--    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Paid</label>
                        <div class="col-lg-9">
                            <input type="text" id="paid" name="paid" class="form-control"
                                placeholder="Please enter Paid">
                        </div>
                    </div>-->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Modal -->

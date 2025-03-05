<!-- Add Modal -->
<div class="modal fade" id="Add_Doctors_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Doctors</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="DoctorsForm" name="DoctorsForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hid" name="hid" value="">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Doctor Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Please enter Doctor name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Specialization</label>
                        <div class="col-lg-9">
                            <select name="specialization" class="form-control" id="specialization">
                                <option value="">Select Specialization</option>
                                @foreach ($specializations as $specialization)
                                    <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Phone</label>
                        <div class="col-lg-9">
                            <input type="text" id="phone" name="phone" class="form-control"
                                placeholder="Please enter Phone">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Email</label>
                        <div class="col-lg-9">
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Please enter email">
                        </div>
                    </div>
                    <div class="form-group
                        row  password-container">
                        <label class="col-lg-3 col-form-label">Password</label>
                        <div class="col-lg-9">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Please enter password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Experience</label>
                        <div class="col-lg-9">
                            <input type="text" id="experience" name="experience" class="form-control"
                                placeholder="Please enter Experience in years">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Qualification</label>
                        <div class="col-lg-9">
                            <input type="text" id="qualification" name="qualification" class="form-control"
                                placeholder="Please enter Qualification">
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
                                                <option value="{{ $country->code }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="City" class="form-control" name="city"
                                            id="city">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="State" class="form-control"
                                            name="state" id="state">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="ZIP code" class="form-control"
                                            name="zip" id="zip">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Image</label>
                        <div class="col-lg-9">
                            <input type="file" id="image" name="image" class="form-control m-b-20"
                                accept="image/*">
                        </div>
                    </div>
                    <div class="col-lg-9" id="priview_image_title" style="display: none;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <label for="img_privew" style="margin-right: 8px; margin-bottom: 0;">Preview Image</label>
                            <i class="fa fa-xmark fa-fw" id="close_icone"
                                style="color: red; cursor: pointer; font-size: 1.2rem;" title="Remove Image"></i>
                        </div>
                        <div>
                            <img id="img_privew" src="#" alt="Image Preview"
                                style="height: 120px; width: auto; margin-top: 10px; border-radius: 5px;" />
                        </div>
                    </div>
                    <div class="col-lg-9" id="oldimgbox">
                        <label>Curent Image</label>
                        <div class="col-sm-10" id="imgbox">
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

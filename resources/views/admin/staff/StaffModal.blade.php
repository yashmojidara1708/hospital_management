<!-- Add Modal -->
<div class="modal fade" id="Add_Staff_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Medicine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="StaffForm" name="StaffForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hid" name="hid" value="">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Name</label>
                        <div class="col-lg-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Please enter staff name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Role</label>
                        <div class="col-lg-9">
                            <div class="row">
                                @foreach ($roles as $role)
                                    <div class="col-lg-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="roles[]"
                                                value="{{ $role->id }}" id="role_{{ $role->id }}">
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="role-error" class="text-danger mt-2"></div>
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
                                        <input type="text" placeholder="State" class="form-control" name="state"
                                            id="state">
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
                        row  password-container">
                        <label class="col-lg-3 col-form-label">password</label>
                        <div class="col-lg-9">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Please enter password">
                        </div>
                    </div>
                    <div class="form-group
                        row">
                        <label class="col-lg-3 col-form-label">Birth Date</label>
                        <div class="col-lg-9">
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                                placeholder="Please enter expiry date">
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

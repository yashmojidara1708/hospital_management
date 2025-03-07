@extends('admin.layouts.index')
@section('admin-title', 'Admin')
@section('center-title', 'Admin')
@section('center-title-route', route('admin.changePassword'))
@section('page-title', 'Admin Profile')
@section('admin-content')
<div class="row">
    <div class="col-md-12">
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-auto profile-image">
                    <a href="#">
                        <img class="rounded-circle" alt="User Image" src="assets/img/profiles/avatar-01.jpg" id="profileImage">
                    </a>
                </div>
                <div class="col ml-md-n2 profile-user-info">
                    <h4 class="user-name mb-0" id="profileName"></h4>
                    <h6 class="text-muted" id="profileEmail"></h6>
                    <h6 class="text-muted" id="profilePhone"></h6>
                    <div class="user-Location" id="profileAddress"><i class="fa fa-map-marker"></i></div>
                    <div class="about-text" id="profileCity">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
					</div>
                <div class="col-auto profile-btn">

                   {{--<a href="{{route('edit.staff')}}" class="btn btn-primary">
                        Edit
                    </a>--}}
                </div>
            </div>
        </div>
        <div class="profile-menu">
            <ul class="nav nav-tabs nav-tabs-solid">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#password_tab">Password</a>
                </li>
            </ul>
        </div>	
        <div class="tab-content profile-tab-cont">
            <!-- Change Password Tab -->
            <div class="tab-pane fade show active" id="password_tab">
            
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Change Password</h5>
                        <div class="row">
                            <div class="col-md-10 col-lg-6">
                                <form id="changepasswordForm" name="changepasswordForm" method="POST">
                                    @csrf
                                    <input type="hidden" id="hid" name="hid">
                                    <div class="form-group">
                                        <label>Old Password</label>
                                        <input type="password" class="form-control" name="oldpassword" id="oldpassword">
                                    </div>
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password" class="form-control" name="newpassword" id="newpassword">
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword">
                                    </div>
                                    <button class="btn btn-primary" type="submit" id="Changepassword">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Change Password Tab -->
            
        </div>
    </div>
</div>
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Changepassword.js') }}"></script>
@endsection

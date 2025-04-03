@extends('doctor.layouts.index')

@section('doctor-page-title', 'Change Password')
@section('page-title', 'Change Password')
@section('page','Change Password')

@section('doctor-content')
<div class="row">
    <div class="col-md-7 col-lg-8 col-xl-9">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!-- Change Password Form -->
                        <form method="POST" id="changePasswordForm">
                            @csrf
                            <div class="form-group">
                                <label>Old Password</label>
                                <input type="password" class="form-control" id="oldpassword" name="oldpassword">
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" id="newpassword" name="newpassword">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword">
                            </div>
                            <button class="btn btn-primary" type="submit" id="changepassword">Save Changes</button>
                        </form>
                        <!-- /Change Password Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('doctor-js')
<script src="{{ asset('assets/admin/theme/js/custom/Doctorpassword.js') }}"></script>
@endsection

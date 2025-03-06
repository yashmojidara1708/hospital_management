@extends('admin.layouts.index')
@section('admin-title', 'Patients Details')
@section('center-title', 'Patients')
@section('center-title-route', route('admin.patients'))
@section('page-title', 'Patients Profile')
@section('admin-content')
    {{-- <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-auto profile-image">
                <a href="#">
                    <img class="rounded-circle" alt="User Image" src="assets/img/profiles/avatar-01.jpg">
                </a>
            </div>
            <div class="col ml-md-n2 profile-user-info">
                <h4 class="user-name mb-0">Ryan Taylor</h4>
                <h6 class="text-muted">ryantaylor@admin.com</h6>
                <div class="user-Location"><i class="fa fa-map-marker"></i> Florida, United States</div>
                <div class="about-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua.</div>
            </div>
        </div>
    </div> --}}
    <div class="tab-content profile-tab-cont">
        <!-- Personal Details Tab -->
        <div class="tab-pane fade show active" id="per_details_tab">
            <!-- Personal Details -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title d-flex justify-content-between">
                                <span>Patients Details</span>
                                {{-- <a class="edit-link" data-toggle="modal" href="javascript:void(0);" id="patientsEdit"
                                    data-id="{{ $patient->patient_id }}"><i class="fa fa-edit mr-1"></i>Edit</a> --}}
                            </h5>
                            <div class="row">
                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Name</p>
                                <p class="col-sm-10">{{ isset($patient->name) ? $patient->name : '' }}
                                </p>
                            </div>
                            <div class="row">
                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Age</p>
                                <p class="col-sm-10">
                                    {{ isset($patient->age) ? $patient->age : '' }}
                                </p>
                            </div>
                            <div class="row">
                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Email ID</p>
                                <p class="col-sm-10">
                                    {{ isset($patient->email) ? $patient->email : '' }}</p>
                            </div>
                            <div class="row">
                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Mobile</p>
                                <p class="col-sm-10">
                                    {{ isset($patient->phone) ? $patient->phone : '' }}
                                </p>
                            </div>
                            <div class="row">
                                <p class="col-sm-2 text-muted text-sm-right mb-0">Address</p>
                                <p class="col-sm-10 mb-0">
                                    {{ isset($patient->address) ? $patient->address : '' }},<br>
                                    {{ isset($patient->city) ? $patient->city : '' }} -
                                    {{ isset($patient->zip) ? $patient->zip : '' }},<br>
                                    {{ isset($patient->state) ? $patient->state : '' }},<br>
                                    {{ isset($patient->country) ? $patient->country : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Personal Details -->
        </div>
        <!-- /Personal Details Tab -->
    </div>
    @include('admin.patients.PatientsModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Patients.js') }}"></script>
@endsection

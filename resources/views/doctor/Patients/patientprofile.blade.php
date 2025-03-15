@extends('doctor.layouts.index')
@section('doctor-page-title', 'Patient Profile')
@section('page-title', 'Patient Profile')
@section('page', 'Patient Profile')
@section('doctor-content')
    <!-- Page Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 dct-appoinment">
                    <div class="card">
                        <div class="card-body pt-0">
                            <div class="user-tabs">
                                <ul class="nav nav-tabs nav-tabs-bottom nav-justified flex-wrap">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#pat_appointments" data-toggle="tab"
                                            data-id="{{ $patient->patient_id }}">Appointments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#pres" data-toggle="tab"
                                            data-id="{{ $patient->patient_id }}"><span>Prescription</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#medical" data-toggle="tab"
                                            data-id="{{ $patient->patient_id }}"><span class="med-records">Medical
                                                Records</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">

                                <!-- Appointment Tab -->
                                <div id="pat_appointments" class="tab-pane fade show active">
                                    <div class="card card-table mb-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Doctor</th>
                                                            <th>Appt Date</th>
                                                            <th>Last Visit</th>
                                                            <th>Status</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="appointment-list">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Appointment Tab -->

                                <!-- Prescription Tab -->
                                <div class="tab-pane fade" id="pres">
                                    <div class="text-right">
                                        <a href="#" class="add-new-btn" id="add-prescription-btn">Add Prescription</a>
                                    </div>
                                    <div class="card card-table mb-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Date </th>
                                                            <th>Name</th>
                                                            <th>Created by </th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="prescription-list">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Prescription Tab -->

                                <!-- Medical Records Tab -->
                                <div class="tab-pane fade" id="medical">
                                    <div class="text-right">
                                        <a href="#" class="add-new-btn" data-toggle="modal"
                                            data-target="#add_medical_records">Add Medical Records</a>
                                    </div>
                                    <div class="card card-table mb-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Date </th>
                                                            <th>Description</th>
                                                            <th>Attachment</th>
                                                            <th>Created</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><a href="javascript:void(0);">#MR-0010</a></td>
                                                            <td>14 Nov 2019</td>
                                                            <td>Dental Filling</td>
                                                            <td><a href="#">dental-test.pdf</a></td>
                                                            <td>
                                                                <h2 class="table-avatar">
                                                                    <a href="doctor-profile.html"
                                                                        class="avatar avatar-sm mr-2">
                                                                        <img class="avatar-img rounded-circle"
                                                                            src="assets/img/doctors/doctor-thumb-01.jpg"
                                                                            alt="User Image">
                                                                    </a>
                                                                    <a href="doctor-profile.html">Dr. Ruby Perrin
                                                                        <span>Dental</span></a>
                                                                </h2>
                                                            </td>
                                                            <td class="text-right">
                                                                <div class="table-action">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-sm bg-primary-light">
                                                                        <i class="fas fa-print"></i> Print
                                                                    </a>
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-sm bg-info-light">
                                                                        <i class="far fa-eye"></i> View
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Medical Records Tab -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- /Page Content -->

@endsection

@section('doctor-js')
    <script src="{{ asset('assets/admin/theme/js/custom/PatientslistDoctor.js') }}"></script>
@endsection

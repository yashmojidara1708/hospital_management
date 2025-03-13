@extends('doctor.layouts.index')
@section('doctor-page-title', 'My Patients')
@section('page-title', 'My Patients')
@section('page', 'My Patients')
@section('doctor-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table table-hover mb-0" id="PatientsTable">
                            <thead class="text-left">
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Last Visit</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('doctor-js')
    <script src="{{ asset('assets/admin/theme/js/custom/PatientslistDoctor.js') }}"></script>
@endsection

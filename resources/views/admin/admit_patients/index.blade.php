@extends('admin.layouts.index')

@section('admin-title', 'Admit patients')
@section('page-title', 'List of Patients')

@section('add-button')
    <div class="col-sm-5 col">
        <a id="add_admit_patient" data-toggle="modal" data-target="#add_admit_patient_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection

@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover mb-0" id="admitPatientTable">
            <thead class="text-left">
                <tr>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Room</th>
                    <th>Admit Date</th>
                    <th>Discharge Date</th>
                    <th>Admit Status</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @include('admin.admit_patients.modal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Admitpatients.js') }}"></script>
@endsection

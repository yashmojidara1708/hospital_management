@extends('admin.layouts.index')
@section('admin-title', 'Patients')
@section('page-title', 'List of Patient')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Patients" data-toggle="modal" data-target="#Add_Patients_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
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
                    <th>Paid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.Patients.PatientsModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Patients.js') }}"></script>
@endsection

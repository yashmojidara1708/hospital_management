@extends('admin.layouts.index')
@section('admin-title', 'Appointments')
@section('page-title', 'List of Appointments')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Appointments" data-toggle="modal" data-target="#Add_Appointments_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover mb-0" id="AppointmentsTable">
            <thead class="text-left">
                <tr>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Patient Name</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.appointments.AppointmentsModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Appointments.js') }}"></script>
@endsection

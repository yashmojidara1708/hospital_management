@extends('admin.layouts.index')
@section('admin-title', 'Doctors')
@section('page-title', 'List of Doctors')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Doctors" data-toggle="modal" data-target="#Add_Doctors_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover mb-0" id="DoctorsTable">
            <thead class="text-left">
                <tr>
                    <th>Image</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Experience</th>
                    <th>Qualification</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.Doctor.DoctorModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Doctors.js') }}"></script>
@endsection

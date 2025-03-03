@extends('admin.layouts.index')
@section('admin-title', 'Staff')
@section('page-title', 'Staff')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Staff" data-toggle="modal" data-target="#Add_Staff_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover mb-0" id="StaffTable">
            <thead class="text-left">
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Birth Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.Staff.StaffModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Staff.js') }}"></script>
@endsection

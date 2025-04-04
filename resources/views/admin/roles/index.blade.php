@extends('admin.layouts.index')
@section('admin-title', 'Role')
@section('page-title', 'Role')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Role" data-toggle="modal" data-target="#Add_Role_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover table-center mb-0" id="roleTable">
            <thead class="text-center">
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.roles.hms_roleModel')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Role.js') }}"></script>
@endsection

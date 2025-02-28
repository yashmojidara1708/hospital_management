@extends('admin.layouts.index')
@section('admin-title')
    Admin | Role
@endsection
@section('page-name')
Role
@endsection
@section('admin-content')
@include('admin.HMS_Role.model')
<div class="table-responsive">
    <table class="datatable table table-hover table-center mb-0" id="roletable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Role</th>
                <th>Status</th>  
                <th>Action</th>  
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>
</div>
</div>
@endsection
@section('user-script')
<script src="{{ asset('assets/admin/theme/custom/role.js') }}"></script>
@endsection   
@extends('admin.layouts.index')
@section('admin-title', 'Specialities')
@section('page-title', 'Specialities')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Specialities" data-toggle="modal" data-target="#Add_Specialities_details"
            class="btn btn-primary float-right mt-2">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover table-center mb-0" id="specialitiesTable">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.specialities.SpecialitiesModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Specialities.js') }}"></script>
@endsection

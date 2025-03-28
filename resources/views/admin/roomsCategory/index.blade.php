@extends('admin.layouts.index')
@section('admin-title', 'Rooms Category')
@section('page-title', 'List of Rooms Category')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="add_rooms" data-toggle="modal" data-target="#add_rooms_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover mb-0" id="roomsTable">
            <thead class="text-left">
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('admin.roomsCategory.modal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/RoomsCategory.js') }}"></script>
@endsection
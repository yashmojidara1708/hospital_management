@extends('admin.layouts.index')

@section('admin-title', 'Rooms')
@section('page-title', 'List of Rooms')

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
                    <th>Room Number</th>
                    <th>Category</th>
                    <th>Beds</th>
                    <th>Charges</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    
    @include('admin.room.modal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Rooms.js') }}"></script>
@endsection

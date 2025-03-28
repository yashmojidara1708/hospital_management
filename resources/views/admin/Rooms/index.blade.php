@extends('admin.layouts.index')
@section('admin-title', 'Rooms')
@section('page-title', 'Rooms')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Room" data-toggle="modal" data-target="#Add_Rooms"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover table-center mb-0" id="roomTable">
            <thead class="text-center">
                <tr>
                    <th>Room Category</th>
                    <th>Total Rooms</th>
                    <th>Beds</th>
                    <th>Charges</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.Rooms.RoomModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Rooms.js') }}"></script>
@endsection

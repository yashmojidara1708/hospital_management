@extends('admin.layouts.index')
@section('admin-title', 'Room Category')
@section('page-title', 'Room Category')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Room" data-toggle="modal" data-target="#Add_Room_category"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover table-center mb-0" id="roomCategoryTable">
            <thead class="text-center">
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.RoomCategory.RoomCategoryModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/RoomCategory.js') }}"></script>
@endsection

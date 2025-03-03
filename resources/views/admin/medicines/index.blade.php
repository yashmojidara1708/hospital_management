@extends('admin.layouts.index')
@section('admin-title', 'Medicines')
@section('page-title', 'List of Medicines')
@section('add-button')
    <div class="col-sm-5 col">
        <a id="Add_Medicines" data-toggle="modal" data-target="#Add_Medicines_details"
            class="btn btn-primary float-right mt-2 text-light">Add</a>
    </div>
@endsection
@section('admin-content')
    <div class="table-responsive">
        <table class="datatable table table-hover mb-0" id="MedicinesTable">
            <thead class="text-left">
                <tr>
                    <th>Medicine Name</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @include('admin.Medicines.MedicinesModal')
@endsection

@section('admin-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Medicines.js') }}"></script>
@endsection

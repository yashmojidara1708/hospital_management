@extends('doctor.layouts.index')
@section('doctor-page-title', 'Prescription')
@section('page-title', 'Prescription')
@section('page','Prescription')
@section('doctor-content')
<div class="row">
    
<div class="col-md-10 col-lg-10 col-xl-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
              <!-- Add Item -->
              <h4 class="card-title mb-0">Prescription</h4>
              <div class="add-more-item">
                  <a href="javascript:void(0);" class="card-title mb-0">
                      <i class="fas fa-plus-circle"></i> Add Prescription
                  </a>
                  <a id="Add_Doctors" data-toggle="modal" data-target="#Add_Doctors_details"
                  class="btn btn-primary float-right mt-2 text-light">Add</a>					
              </div>
             <!-- /Add Item -->   
        </div>
        <div class="card-body"> 
            <div class="row">
                <div class="table-responsive">
                    <table class="datatable table table-hover mb-0" id="PatientsTable">
                        <thead class="text-left">
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Age</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Last Visit</th>
                            </tr>
                        </thead>
                        <tbody>
            
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>     
</div>
@include('doctor.Prescription.modal')
@endsection
@section('doctor-js')
<script src="{{ asset('assets/admin/theme/js/custom/Prescription.js') }}"></script>
@endsection

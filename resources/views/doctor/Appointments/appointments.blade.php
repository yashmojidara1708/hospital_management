@extends('doctor.layouts.index')
@section('doctor-page-title', 'Appointments')
@section('page-title', 'Appointments')
@section('page','Appointments')
@section('doctor-content')
<div class="row">
    <div class="col-md-7 col-lg-8 col-xl-9">
        <div class="appointments">
     @foreach ($appointments as $appointment)
        <!-- Appointment List -->
            <div class="appointment-list">
             
                <div class="profile-info-widget">
                    <a href="patient-profile.html" class="booking-doc-img">
                        <img src="assets/img/patients/patient.jpg" alt="User Image">
                    </a>
                    <div class="profile-det-info">
                        <h3><a href="patient-profile.html"></a>{{$appointment->patient_name}}</h3>
                        <div class="patient-details">
                            <h5><i class="far fa-calendar-alt"></i>
                                {{\Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}
                               
                            </h5>
                            <h5><i class="far fa-clock"></i>
                                {{\Carbon\Carbon::parse($appointment->time)->format('h:i, A') }}
                            </h5>
                            <h5><i class="fas fa-phone"></i> +91 {{$appointment->patient_phone}}</h5>
             
                            <h5><i class="fas fa-envelope"></i>{{$appointment->patient_email}} </h5>
                         
                            <h5><i class="fas fa-map-marker-alt"></i>
                                    {{$appointment->patient_address}}</h5>
                                    <h5 class="text-muted" style="margin-left: 20px;">
                                    {{$appointment->city_name}},{{$appointment->state_name}},
                                    {{$appointment->country_name}}
                                    </h5>
                        </div>
                    </div>
                </div>
                <div class="appointment-action">
                    <a id="Add_Appointments" data-target="#Add_Appointments_details"
                   class="btn btn-sm bg-primary-light" data-id="{{ $appointment->id }}">
                   <i class="far fa-eye"></i> 
                   </a>

                   <a href="javascript:void(0);" class="btn btn-sm bg-success-light">
                       <i class="fas fa-check"></i>
                   </a>
                   <a href="javascript:void(0);" class="btn btn-sm bg-danger-light">
                       <i class="fas fa-times"></i>
                   </a>

               </div>
            </div>
            @endforeach 
        </div>
       
    </div>
    
</div>
<!-- Add Modal -->
<div class="modal fade" id="Add_Appointments_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Appointment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="info-details">
                    <li>
                        <div class="details-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <span>Appointment Date and Time</span>
                                    <span class="title" id="app_id"></span>
                                    <span class="text" id="app_date"></span>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-right">
                                        <button type="button" class="btn bg-success-light btn-sm" id="topup_status"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <span class="title">Status:</span>
                        <span class="text" id="status"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Modal -->

@endsection

@section('doctor-js')
<script src="{{ asset('assets/admin/theme/js/custom/AppointmentDoctor.js') }}"></script>
@endsection

@extends('doctor.layouts.index')

@section('doctor-page-title', 'Appointments')
@section('page-title', 'Appointments')
@section('page','Appointments')

@section('doctor-content')
<div class="row">
    <div class="col-md-7 col-lg-8 col-xl-9">
        <div class="appointments">
        
            <!-- Appointment List -->
            @foreach ($appointments as $appointment)
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
                    <a href="#" class="btn btn-sm bg-info-light" data-toggle="modal" data-target="#appt_details">
                        <i class="far fa-eye"></i> View
                    </a>
                    <a href="javascript:void(0);" class="btn btn-sm bg-success-light">
                        <i class="fas fa-check"></i> Accept
                    </a>
                    <a href="javascript:void(0);" class="btn btn-sm bg-danger-light">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
            @endforeach
            <!-- /Appointment List -->
         
        </div>
    </div>
</div>
@endsection

@section('doctor-js')

@endsection

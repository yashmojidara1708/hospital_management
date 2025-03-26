@extends('doctor.layouts.index')
@section('doctor-page-title', 'Appointments')
@section('page-title', 'Appointments')
@section('page', 'Appointments')
@section('doctor-content')
    <div class="row">
        <div class="col-md-7 col-lg-8 col-xl-9">
            <div class="appointments">
                @if ($appointments->isEmpty())
                    <!-- No Appointments Message -->
                    <div class="text-center py-5">
                        <h4 class="text-muted">No Appointments Available</h4>
                    </div>
                @else
                    @foreach ($appointments as $appointment)
                        <!-- Appointment List -->
                        <div class="appointment-list">
                            <div class="profile-info-widget">
                                <a href="patient-profile.html" class="booking-doc-img">
                                    <img src="assets/img/patients/patient.jpg" alt="User Image">
                                </a>
                                <div class="profile-det-info">
                                    <h3>{{ $appointment->patient_name }}</h3>
                                    <div class="patient-details">
                                        <h5><i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}
                                        </h5>
                                        <h5><i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}
                                        </h5>
                                        <h5><i class="fas fa-phone"></i> +91 {{ $appointment->patient_phone }}</h5>
                                        <h5><i class="fas fa-envelope"></i> {{ $appointment->patient_email }}</h5>
                                        <h5><i class="fas fa-map-marker-alt"></i> 
                                            {{ $appointment->patient_address }}
                                        </h5>
                                        <h5 class="text-muted" style="margin-left: 20px;">
                                            {{ $appointment->city_name }}, {{ $appointment->state_name }},
                                            {{ $appointment->country_name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="appointment-action">
                                <a id="Add_Appointments" data-target="#Add_Appointments_details"
                                    class="btn btn-sm bg-primary-light" data-id="{{ $appointment->id }}">
                                    <i class="far fa-eye"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm bg-success-light mark-complete" data-id="{{ $appointment->id }}">
                                    <i class="fas fa-check"></i>
                                </a>

                                <a href="javascript:void(0);" class="btn btn-sm bg-danger-light appoinment-delete" data-id="{{ $appointment->id }}">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@section('modal-content')
    <!-- Add Modal -->
    @include('doctor.Appointments.modal')
    <!-- /ADD Modal -->

    <div id="rejectAppointmentModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea id="rejectionReason" class="form-control" placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmRejectAppointment">Reject</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@endsection

@section('doctor-js')
<script src="{{ asset('assets/admin/theme/js/custom/AppointmentDoctor.js') }}"></script>
@endsection

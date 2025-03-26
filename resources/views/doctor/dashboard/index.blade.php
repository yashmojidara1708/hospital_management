@php
    $DoctorDatas = session('doctors_data');
    $DrIds = isset($DoctorDatas['id']) ? $DoctorDatas['id'] : '';
@endphp
<style>
    .custom-checkbox {
        padding-left: 1.5rem;
    }

    .custom-control-label::before,
    .custom-control-label::after {
        top: 0.15rem;
        left: -1.5rem;
        width: 1.2rem;
        height: 1.2rem;
    }

    .custom-control-input:checked~.custom-control-label::before {
        background-color: #09e5ab;
        border-color: #09e5ab;
    }

    .custom-control-input:focus~.custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(9, 229, 171, 0.25);
    }

    /* Remove !important to allow jQuery to update the display */
</style>
@extends('doctor.layouts.index')
@section('doctor-page-title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('doctor-content')
    <div class="col-md-12">
        <div class="card dash-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <div class="dash-widget dct-border-rht">
                            <div class="circle-bar circle-bar1">
                                <div class="circle-graph1" data-percent="75">
                                    <img src="assets/img/icon-01.png" class="img-fluid" alt="patient">
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6>Total Patient</h6>
                                <h3>{{$totalPatientCount}}</h3>
                                <p class="text-muted">Till Today</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="dash-widget dct-border-rht">
                            <div class="circle-bar circle-bar2">
                                <div class="circle-graph2" data-percent="65">
                                    <img src="assets/img/icon-02.png" class="img-fluid" alt="Patient">
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6>Today's Patient</h6>
                                <h3>{{$todayPatients}}</h3>
                                <p class="text-muted">{{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-4">
                        <div class="dash-widget">
                            <div class="circle-bar circle-bar3">
                                <div class="circle-graph3" data-percent="50">
                                    <img src="assets/img/icon-03.png" class="img-fluid" alt="Patient">
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6>Total Appoinments</h6>
                                <h3>{{$totalAppointments}}</h3>
                                <p class="text-muted">{{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4">Upcomming Patient Appoinment </h4>
            <div class="card-footer" id="bulkActionsContainer" style="display: none;">
                <input type="hidden" id="selectedAppointments" name="selectedAppointments" value="">
                <button class="btn btn-success mr-2" id="bulkApprove">
                    <i class="fas fa-check"></i> Approve Selected
                </button>
                <button class="btn btn-danger" id="bulkReject">
                    <i class="fas fa-times"></i> Reject Selected
                </button>
            </div>
            <div class="appointment-tab">
                <div class="tab-content">
                    <!-- Upcoming Appointment Tab -->
                    <div class="tab-pane show active" id="upcoming-appointments">
                        <div class="card card-table mb-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-center mb-0">
                                        <thead>
                                            <tr>
                                                <th width="5%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="selectAll">
                                                        <label class="custom-control-label" for="selectAll"></label>
                                                    </div>
                                                </th>
                                                <th>Patient Name</th>
                                                <th>Appt Date</th>
                                                <th>Phone</th>
                                                <th>Last visit Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="appointment-table-body">
                                            @if (!empty($appointments) && count($appointments) > 0)
                                                @foreach ($appointments as $appointment)
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input row-checkbox" 
                                                                    id="appointment_{{ $appointment->id }}" 
                                                                    data-id="{{ $appointment->id }}">
                                                                <label class="custom-control-label" for="appointment_{{ $appointment->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h2 class="table-avatar">
                                                                <a id="patientprofile" href="javascript:void(0);" class="view-patient-profile" 
                                                                data-id="{{ $appointment->patient_id }}">{{ isset($appointment->patient_name) ? $appointment->patient_name : 'N|A' }}
                                                                </a>
                                                            </h2>
                                                        </td>
                                                        <td>
                                                            {{ isset($appointment->date) ? $appointment->date : 'N|A' }}
                                                            <span class="d-block text-info">{{ $appointment->time }}</span>
                                                        </td>
                                                        <td>{{ isset($appointment->phone) ? $appointment->phone : 'N|A' }}</td>
                                                        <td>{{ isset($appointment->last_visit) ? $appointment->last_visit : '' }}</td>
                                                        <td class="text-right">
                                                            <div class="table-action">
                                                                <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                                                    <i class="far fa-eye"></i>
                                                                </a>
                                                                <a href="javascript:void(0);" class="btn btn-sm bg-success-light">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="javascript:void(0);" class="btn btn-sm bg-danger-light">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7">
                                                        <h6 class="text-center">No Appointments Found at this time</h6>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>

    
@section('modal-content')

{{-- single reject appoinment --}}
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


{{-- bulk reject appoinment --}}
<div id="rejectBulkAppointmentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Appointment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="customReasonCheckbox">
                    <label class="form-check-label" for="customReasonCheckbox">Add Custom Rejection Reason</label>
                </div>
                <textarea id="bulkRejectionReasonTextarea" class="form-control mt-3" placeholder="Enter rejection reason..." style="display: none;"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBulkRejectAppointment">Reject</button>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection

@section('doctor-js')
<script src="{{ asset('assets/admin/theme/js/custom/PatientslistDoctor.js') }}"></script>
    <script>
        Pusher.logToConsole = true;
        var loggedInDoctorId = "{{ $DrIds }}";
        var pusher = new Pusher('a64fd3494d4e33ee738a', {
            cluster: 'ap2',
            encrypted: false
        });
        console.log("loggedInDoctorId", loggedInDoctorId);


        var channel = pusher.subscribe('doctor-channel');
        channel.bind('patient-assigned', function(data) {
            const patientName = data?.appointment?.patient_name || "Unknown";
            const date = data?.appointment?.date || "N/A";
            const time = data?.appointment?.time || "N/A";

            if (data?.appointment && data?.appointment?.doctor == loggedInDoctorId) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "info",
                    title: `📢 New Patient Assigned!`,
                    html: `<b>👤 ${patientName} | 📅 ${date} | ⏰ ${time}</b>`,
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true,
                    background: "#f0f9ff",
                    color: "#1e293b",
                    customClass: {
                        popup: "swal2-toast-custom"
                    }
                });
                fetchUpdatedAppointments();
            }
        });

        // function refreshAppointmentTable() {
        //     $('#appointment-table-body').load(window.location.href + ' #appointment-table-body > *');
        // }

        // refreshAppointmentTable();

        function fetchUpdatedAppointments() {
            $.ajax({
                url: "{{ route('doctor.fetchAppointments') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        updateAppointmentTable(response.appointments);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching updated appointments:", error);
                }
            });
        }

        function updateAppointmentTable(appointments) {
            let tableBody = $("#appointment-table-body");
            tableBody.empty(); // Clear old data

            if (appointments.length > 0) {
                appointments.forEach(appointment => {
                    let row = `
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input row-checkbox" 
                                        id="appointment_${appointment.id}" 
                                        data-id="${appointment.id}">
                                    <label class="custom-control-label" for="appointment_${appointment.id}"></label>
                                </div>
                            </td>
                            <td>
                                <h2 class="table-avatar">
                                    <a href="javascript:void(0);" class="view-patient-profile" 
                                    data-id="${appointment.patient_id}">${appointment.patient_name || 'N|A'}</a>
                                </h2>
                            </td>
                            <td>${appointment.date || 'N|A'}
                                <span class="d-block text-info">${appointment.time}</span>
                            </td>
                            <td>${appointment.phone || 'N|A'}</td>
                            <td>${appointment.last_visit || ''}</td>
                            <td class="text-right">
                                <div class="table-action">
                                    <a href="javascript:void(0);" class="btn btn-sm bg-info-light">
                                        <i class="far fa-eye"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm bg-success-light mark-complete" data-id="${appointment.id}">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm bg-danger-light appoinment-delete" data-id="${appointment.id}">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                    tableBody.append(row);
                });
            } else {
                tableBody.html('<tr><td colspan="7" class="text-center">No Appointments Found at this time</td></tr>');
            }
            
            // Reinitialize checkbox functionality after table update
            // initCheckboxFunctionality();
        }

        // Initial load
        fetchUpdatedAppointments();
    </script>
@endsection

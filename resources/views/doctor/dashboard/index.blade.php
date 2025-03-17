@php
    $DoctorDatas = session('doctors_data');
    $DrIds = isset($DoctorDatas['id']) ? $DoctorDatas['id'] : '';
@endphp

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
                                                            <h2 class="table-avatar">
                                                                <a  id="patientprofile" href="javascript:void(0);" class="view-patient-profile" 
                                                                data-id="{{ $appointment->patient_id }}">{{ isset($appointment->patient_name) ? $appointment->patient_name : 'N|A' }}
                                                                </a>
                                                            </h2>
                                                        </td>
                                                        <td>
                                                            {{ isset($appointment->date) ? $appointment->date : 'N|A' }}
                                                            <span class="d-block text-info">{{ $appointment->time }}</span>
                                                        </td>
                                                        <td>{{ isset($appointment->phone) ? $appointment->phone : 'N|A' }}
                                                        </td>
                                                        <td>{{ isset($appointment->last_visit) ? $appointment->last_visit : '' }}
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="table-action">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-sm bg-info-light">
                                                                    <i class="far fa-eye"></i>
                                                                </a>
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-sm bg-success-light">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-sm bg-danger-light">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6">
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
            }
        });

        function refreshAppointmentTable() {
            $('#appointment-table-body').load(window.location.href + ' #appointment-table-body > *');
        }

        refreshAppointmentTable();
    </script>
@endsection

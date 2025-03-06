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
                                <h3>1500</h3>
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
                                <h6>Today Patient</h6>
                                <h3>160</h3>
                                <p class="text-muted">06, Nov 2019</p>
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
                                <h6>Appoinments</h6>
                                <h3>85</h3>
                                <p class="text-muted">06, Apr 2019</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4">Upcomming Patient Appoinment</h4>
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
                                                <th class="text-center">Paid Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($appointments) && count($appointments) > 0)
                                                @foreach ($appointments as $appointment)
                                                    <tr>
                                                        <td>
                                                            <h2 class="table-avatar">

                                                                <a href="patient-profile.html">{{ isset($appointment->patient_name) ? $appointment->patient_name : 'N|A' }}
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
                                                        <td class="text-center">
                                                            {{ isset($appointment->amount) ? $appointment->amount : '' }}
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

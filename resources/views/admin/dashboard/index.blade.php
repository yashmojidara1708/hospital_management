@extends('admin.layouts.index')
@section('admin-title', 'Dashboard')
@section('page-title', 'Dashboard')
@php
    $titles = ['Doctors', 'Staffs', 'Patients'];
    $icons = ['fe fe-users', 'fa-solid fa-users', 'fe fe-user-plus'];
    $colors = ['text-primary', 'text-success', 'text-warning'];
    $border = ['border-primary', 'border-success', 'border-warning'];
    $progressColors = ['bg-primary', 'bg-success', 'bg-warning'];
@endphp
@section('admin-content')
    <div class="row">
        @foreach ($counts as $key => $count)
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon {{ $colors[$loop->index] }} {{ $border[$loop->index] }}">
                                <i class="{{ $icons[$loop->index] }}"></i>
                            </span>
                            <div class="dash-count">
                                <h3>{{ $count }}</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">{{ $titles[$loop->index] }}</h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar {{ $progressColors[$loop->index] }} w-50"
                                    style="border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-6 d-flex">

            <!-- Recent Orders -->
            <div class="card card-table flex-fill">
                <div class="card-header">
                    <h4 class="card-title">Doctors List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th>Speciality</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($Alldoctors->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">No available data</td>
                                    </tr>
                                @else
                                    @foreach ($Alldoctors as $doctor)
                                        <tr>
                                            <td>{!! $doctor->avatar !!}</td>
                                            <td>{{ $doctor->specialization_name ?? 'N/A' }}</td>
                                            <td>{{ $doctor->phone ?? 'N/A' }}</td>
                                            <td>{{ $doctor->email ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Recent Orders -->

        </div>
        <div class="col-md-6 d-flex">

            <!-- Feed Activity -->
            <div class="card  card-table flex-fill">
                <div class="card-header">
                    <h4 class="card-title">Patients List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Last Visit</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($Allpatients->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">No available data</td>
                                    </tr>
                                @else
                                    @foreach ($Allpatients as $patients)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="" class="avatar avatar-sm mr-2">
                                                        <img src="{{ asset('assets/admin/theme/img/profiles/avatar-06.png') }} "
                                                            width="50" height="50" class="rounded-circle"
                                                            alt="User Image">
                                                    </a>
                                                    <a href="">{{ e($patients->name) }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $patients->last_visit ?? 'N/A' }}</td>
                                            <td>{{ $patients->phone ?? 'N/A' }}</td>
                                            <td>{{ $patients->email ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Feed Activity -->

        </div>
    </div>
@endsection

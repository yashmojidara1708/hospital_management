@extends('admin.layouts.index')
@section('admin-title', 'Dashboard')
@section('page-title', 'Dashboard')
@php
    $titles = ['Doctors', 'Staffs', 'Patients'];
    $icons = ['fe fe-users', 'fa-solid fa-users', 'fe fe-user-plus'];
@endphp
@section('admin-content')
    <div class="row">
        @foreach ($counts as $key => $count)
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon text-primary border-primary">
                                <i class="{{ $icons[$loop->index] }}"></i>
                            </span>
                            <div class="dash-count">
                                <h3>{{ $count }}</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">{{ $titles[$loop->index] }}</h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary w-50"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

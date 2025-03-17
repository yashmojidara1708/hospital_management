@extends('doctor.layouts.index')
@section('doctor-page-title', 'Prescription')
@section('page-title', 'Prescription')
@section('page', 'Prescription')
@section('doctor-content')
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Add Prescription</h4>
                {{-- <a href="" class="btn btn-secondary d-flex align-items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                </a> --}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="biller-info d-flex align-items-center">
                            <div>
                                <h4 class="mb-1">Dr. {{ $doctor->name ?? 'N/A' }}</h4>
                                <span class="text-muted d-block">{{ $doctor->specialization ?? 'N/A' }}</span>
                                <span class="text-muted d-block">
                                    {{ $doctor->city ?? 'N/A' }}, {{ $doctor->state ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 text-md-right">
                        <div class="billing-info">
                            <h4 class="mb-1">Patient Details</h4>
                            <span class="text-muted d-block">
                                <strong>Patient Name:</strong> {{ $patient->name ?? 'N/A' }}
                            </span>
                            <span class="text-muted d-block">
                                <strong>age:</strong> {{ $patient->age ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!--prescription form-->
                <form onsubmit="return false" method="POST" id="prescription-form" name="prescription-form"
                    enctype="multipart/form-data">
                    <input type="hidden" name="patient_id" value="{{ $patient->patient_id }}">
                    @csrf
                    <!-- Add Item -->
                    <div class="text-right">
                        <a href="javascript:void(0);" class="add-more-item" style="color: #0de0fe;"><i
                                class="fas fa-plus-circle"></i> Add
                            Medicine</a>
                    </div>
                    <!-- /Add Item -->
                    <div class="card card-table mt-2">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 200px">Name</th>
                                            <th style="min-width: 100px">Quantity</th>
                                            <th style="min-width: 100px">Days</th>
                                            <th style="min-width: 100px;">Time</th>
                                            <th style="min-width: 80px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="prescription-items">
                                        <tr>
                                            <td>
                                                <select class="form-control medicine-select"
                                                    name="medicine_name[]"></select>
                                            </td>
                                            <td><input class="form-control" type="number" name="quantity[]">
                                            </td>
                                            <td><input class="form-control" type="number" name="days[]"></td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="time[morning][]">
                                                    Morning
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="time[afternoon][]"> Afternoon
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="time[evening][]">
                                                    Evening
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="time[night][]">
                                                    Night
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn bg-danger-light remove-medicine"><i
                                                        class="far fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Instruction Field -->
                            <div class="form-group">
                                <label for="instructions">Instructions</label>
                                <textarea class="form-control" id="instructions" name="instructions" rows="2" placeholder="Enter instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    <!-- /Submit Section -->
                </form>
            </div>
        </div>
    </div>
@endsection
@section('modal-content')
    <!-- Add Modal -->
    @include('doctor.Prescription.modal')
    <!-- /ADD Modal -->
@endsection

@section('doctor-js')
    <script src="{{ asset('assets/admin/theme/js/custom/Prescription.js') }}"></script>
@endsection

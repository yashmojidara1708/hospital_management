@extends('doctor.layouts.index')
@section('doctor-page-title', 'Prescription')
@section('page-title', 'Prescription')
@section('page', 'Prescription')
@section('doctor-content')
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Prescription</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="biller-info">
                                <h4 class="d-block">Dr.{{$doctor->name}}</h4>
                                <span class="d-block text-sm text-muted">{{$doctor->specialization}}</span>
                                <span class="d-block text-sm text-muted">{{$doctor->city}},{{$doctor->state}}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 text-sm-right">
                            <div class="billing-info">
                                <h4 class="d-block">Prescription Number</h4>
                            </div>
                        </div>
                    </div>
            <!--prescription form-->
                    <form id="prescription-form">
                    <div class="row">
                        <div class="col-md-12">
                                        <!-- Date Field -->
                                        <!-- Instruction Field -->
                                        <div class="form-group">
                                            <label for="instructions">Instructions</label>
                                            <textarea class="form-control" id="instructions" name="instructions" rows="2" placeholder="Enter instructions..."></textarea>
                                        </div>
                        </div>
                    </div>
                <!-- Add Item -->
                <div class="add-more-item text-right">
                    <a href="javascript:void(0);"><i class="fas fa-plus-circle"></i> Add Medicine</a>
                </div>
                <!-- /Add Item -->
                        <div class="card card-table">
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
                                                    <select class="form-control medicine-select" name="medicine_name[]" required></select>
                                                </td>
                                                <td><input class="form-control" type="number" name="quantity[]" required></td>
                                                <td><input class="form-control" type="number" name="days[]" required></td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="time[morning][]"> Morning
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="time[afternoon][]"> Afternoon
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="time[evening][]"> Evening
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="time[night][]"> Night
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn bg-danger-light remove-medicine"><i class="far fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                   </div>
                            </div>
                        </div>
                        <!-- Submit Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save</button>
                                <button type="reset" class="btn btn-secondary submit-btn">Clear</button>
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

@extends('admin.layouts.index')

@section('admin-title', 'Patient Bill')
@section('page-title', 'Patient Bill')

@section('admin-content')
    <div class="col-lg-12 offset-lg-0">
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <button id="save-bill-btn" class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-file-medical"></i>&nbsp;Save Bill
            </button>
        </div>

        <div id="invoice-container">
            <div class="invoice-content">
                <div class="invoice-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="invoice-logo">
                                @if (!empty(get_setting('company_logo')))
                                    @if (get_setting('company_logo'))
                                        <img class="" src="{{ asset('uploads/' . get_setting('company_logo')) }}"
                                            alt="Hospital Logo">
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="invoice-details">

                                <strong> Date: {{ date('d-M-Y', strtotime(today())) }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Patient Details on the Left -->
                    <div class="col-md-6">
                        <div class="invoice-info">
                            <h5><strong class="customer-text">Invoice To</strong></h5>
                            <p class="mb-1">{{ $patientdetail->patient_name ?? 'N/A' }}</p>

                            <div class="float-left">
                                <h6><strong class="customer-text">Patient Details</strong></h6>
                                <p class="mb-1"><i class="fa fa-user"></i> {{ $patientdetail->patient_name ?? 'N/A' }}</p>
                                <p class="mb-1"><i class="fa fa-map-marker"></i>
                                    {{ $patientdetail->patient_address ?? 'N/A' }}, <br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $patientdetail->city_name ?? 'N/A' }},
                                    {{ $patientdetail->state_name ?? 'N/A' }}</p>
                                <p class="mb-1"><i class="fa fa-phone"></i> {{ $patientdetail->patient_phone ?? 'N/A' }}
                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Doctor Details on the Right -->
                    <div class="col-md-6">
                        <div class="invoice-info invoice-info2">

                            <div class="float-right">
                                <h5><strong class="customer-text">Doctor Details</strong></h5>
                                <p class="mb-1"><i class="fa fa-user-md"></i> Dr.
                                    {{ $patientdetail->doctor_name ?? 'N/A' }}</p>
                                <p class="mb-1"><i class="fa fa-user-graduate"></i>
                                    {{ $patientdetail->specialization_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason for Admission (Separate Section) -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h4><i class="fa fa-notes-medical"></i> Reason for Admission</h4>
                            <p class="text-muted">{{ $patientdetail->admission_reason ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="invoice-item invoice-table-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <!-- Room & Admission Details -->
                                <h5 class="mt-4 mb-2">Room & Admission Details</h5>
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th>Room</th>
                                            <th>Room Number</th>
                                            <th>Date of Admission</th>
                                            <th>Discharge Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>{{ $patientdetail->room_category ?? 'N/A' }}</td>
                                            <td>{{ $patientdetail->room_number ?? 'N/A' }}</td>
                                            <td>{{ date('d-M-Y', strtotime($patientdetail->admit_date)) ?? 'N/A' }}</td>
                                            <td>{{ $patientdetail->discharge_date ? date('d-M-Y', strtotime($patientdetail->discharge_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Billing Details -->
                                <h5 class="mt-4 mb-2">Billing Details</h5>
                                <table class="table table-bordered">
                                    <thead class="thead-light">

                                        @php
                                            $doctor_fee = 200;
                                            $room_charge = $patientdetail->charges;
                                            $days_admitted = $patientdetail->days_admitted;
                                            $room_total = $room_charge * $days_admitted;
                                            $doctor_total = $doctor_fee * $days_admitted;
                                            if ($days_admitted == 0) {
                                                $discount = 0;
                                                $room_total = $room_charge;
                                                $doctor_total = $doctor_fee;
                                                $subtotal = $room_total + $doctor_total;
                                                $discountamount = ($subtotal * 0) / 100;
                                                $total = $subtotal - $discountamount;
                                            } else {
                                                /* $discount=10;
                $discountamount=(($subtotal*10)/100);
                $total=$subtotal-$discountamount;  */
                                            }

                                        @endphp

                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Description</th>
                                            <th>Amount(₹)</th>
                                            @if ($days_admitted >= 1)
                                                <th>Days Admitted</th>
                                            @endif
                                            <th>Sub Total (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>1</td>
                                            <td>Room Charges</td>
                                            <td>{{ number_format($room_charge, 2) }}</td>
                                            @if ($days_admitted >= 1)
                                                <td>{{ $days_admitted }}</td>
                                            @endif
                                            <td>{{ number_format($room_total, 2) }}</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td>2</td>
                                            <td>Doctor Fees</td>
                                            <td>{{ number_format($doctor_fee, 2) }}</td>
                                            @if ($days_admitted >= 1)
                                                <td>{{ $days_admitted }}</td>
                                            @endif
                                            <td>{{ number_format($doctor_total, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>

                                        @if ($days_admitted == 0)
                                            <tr>
                                                <th colspan="3" class="text-right">Sub Total</th>
                                                <th class="text-center" id="sub_total">
                                                    <span id="sub_total">₹0.00</span>
                                                </th>
                                            </tr>
                                        @else
                                            <tr>
                                                <th colspan="4" class="text-right">Sub Total</th>
                                                <th class="text-center" id="sub_total">
                                                    <span id="sub_total">₹0.00</span>
                                                </th>
                                            </tr>
                                        @endif
                                        @if ($days_admitted == 0)
                                            <tr>
                                                <th colspan="3" class="text-right text-danger">
                                                    Discount (%)
                                                    <input type="number" id="discount_percentage"
                                                        class="form-control d-inline w-25 ml-2"
                                                        value="{{ $discount ?? 0 }}" min="0" max="100"
                                                        step="1">
                                                </th>
                                                <th class="text-center text-danger">
                                                    <span id="discount_amount">- ₹0.00</span>
                                                </th>
                                            </tr>
                                        @else
                                            <tr>
                                                <th colspan="4" class="text-right text-danger">
                                                    Discount (%)
                                                    <input type="number" id="discount_percentage"
                                                        class="form-control d-inline w-25 ml-2"
                                                        value="{{ $discount ?? 0 }}" min="0" max="100"
                                                        step="1">
                                                </th>
                                                <th class="text-center text-danger">
                                                    <span id="discount_amount">- ₹0.00</span>
                                                </th>
                                            </tr>
                                        @endif
                                        @if ($days_admitted == 0)
                                            <tr>
                                                <th colspan="3" class="text-right">Grand Total</th>
                                                <th class="text-center text-success">
                                                    <span id="grand_total">₹0.00</span>
                                                </th>
                                            </tr>
                                        @else
                                            <tr>
                                                <th colspan="4" class="text-right">Grand Total</th>
                                                <th class="text-center text-success">
                                                    <span id="grand_total">₹0.00</span>
                                                </th>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="other-info mt-3">
                    <h4 class="d-flex align-items-center">
                        <i class="fa-solid fa-money-bill-wave text-success mr-2"></i> Total Payable Amount
                    </h4>
                    <p class="text-muted mb-1"><strong> <span id="grand_total_display"></span></strong></p>
                    <p class="text-muted"><em><span id="grand_total_in_words"></span></em></p>
                </div>

                <div class="other-info">
                    <h4>Other Information</h4>
                    <p class="text-muted mb-0">Thank you for trusting our medical services. Should you have any questions
                        regarding your treatment or this invoice, please feel free to contact our billing department. We are
                        committed to providing you with the highest level of care and support.
                    </p>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a onclick="printInvoice()" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-print"></i>&nbsp; Print
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form id="bill-form" method="post">
        @csrf
        <input type="hidden" name="admitted_id" value="{{ $patientdetail->id }}">
        <input type="hidden" name="patient_id" value="{{ $patientdetail->patient_id }}">
        <input type="hidden" name="doctor_id" value="{{ $patientdetail->doctor_id }}">
        <input type="hidden" name="room_number" value="{{ $patientdetail->room_number }}">
        <input type="hidden" name="admission_date" value="{{ $patientdetail->admit_date }}">
        <input type="hidden" name="discharge_date" value="{{ $patientdetail->discharge_date }}">
        <input type="hidden" name="total_days" value="{{ $patientdetail->days_admitted }}">
        <input type="hidden" id="room_charge" name="room_charge" value="{{ $room_total }}">
        <input type="hidden" id="doctor_fee" name="doctor_fee" value="{{ $doctor_total }}">
        <input type="hidden" id="discount" name="discount" value="0">
        <input type="hidden" id="discount_amount_hidden" name="discount_amount_hidden" value="0">
        <input type="hidden" id="total_amount" name="total_amount" value="0">

    </form>

@endsection

@section('admin-js')
    <script>
        function printInvoice() {
            // Get the container
            var container = document.getElementById("invoice-container");

            // Clone it to avoid changing the original DOM
            var clone = container.cloneNode(true);

            // Replace all input[type=number] with span elements showing their value
            var numberInputs = clone.querySelectorAll('input[type="number"]');
            numberInputs.forEach(function(input) {
                var valueSpan = document.createElement("span");
                valueSpan.textContent = input.value;
                input.parentNode.replaceChild(valueSpan, input);
            });

            // Get the modified HTML for printing
            var printContent = clone.innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }
    </script>

    <script src="{{ asset('assets/admin/theme/js/custom/PatientBill.js') }}"></script>
@endsection

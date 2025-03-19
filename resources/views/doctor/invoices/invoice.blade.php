@extends('doctor.layouts.index')

@section('doctor-page-title', 'Invoice')
@section('page-title', 'Invoice')

@section('doctor-content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 offset-lg-0">
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
                <div id="invoice-container">
                    <div class="invoice-content">
                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-logo">
                                        <img src="{{ asset('/assets/img/logo-white.png') }}" alt="logo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="invoice-details">
                                        <strong>Order:</strong> #{{ $prescription->id ?? "#" }} <br>
                                        <strong>Issued:</strong> {{ $prescription->created_at ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-info">
                                        <strong class="customer-text">Invoice From</strong>
                                        <p class="invoice-details invoice-details-two">
                                            Dr. {{ $doctorData->name ?? 'N/A' }} <br>
                                            {{ $doctorData->address ?? 'N/A' }} <br>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="invoice-info invoice-info2">
                                        <strong class="customer-text">Invoice To</strong>
                                        <p class="invoice-details">
                                            Patient ID: {{ $prescription->patient_id ?? 'N/A' }} <br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-item invoice-table-wrap">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="invoice-table table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Price</th>
                                                    <th class="text-right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($medicines) && count($medicines) > 0)
                                                    @foreach($medicines as $medicine)
                                                        <tr>
                                                            <td>{{ $medicine->name ?? 'N/A' }}</td>
                                                            <td class="text-center">{{ $medicine->quantity ?? 'N/A' }}</td>
                                                            <td class="text-center">${{ $medicine->price ?? '0.00' }}</td>
                                                            <td class="text-right">${{ ($medicine->quantity ?? 1) * ($medicine->price ?? 0) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center">No medicines found.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4 ml-auto">
                                    <div class="table-responsive">
                                        <table class="invoice-table-two table">
                                            <tbody>
                                                <tr>
                                                    <th>Subtotal:</th>
                                                    <td><span>${{ $medicines->sum(fn($med) => ($med->quantity ?? 1) * ($med->price ?? 0)) }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Discount:</th>
                                                    <td><span>-10%</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Amount:</th>
                                                    <td><span>${{ $medicines->sum(fn($med) => ($med->quantity ?? 1) * ($med->price ?? 0)) * 0.9 }}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="other-info">
                            <h4>Other Information</h4>
                            <p class="text-muted mb-0">Thank you for your visit. Please contact us if you have any questions.</p>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a onclick="printInvoice()" class="btn btn-outline-primary d-flex align-items-center">
                                <i class="fas fa-arrow-right mr-2"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function printInvoice() {
        var printContent = document.getElementById("invoice-container").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>
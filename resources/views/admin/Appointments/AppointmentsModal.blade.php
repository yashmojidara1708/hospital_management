<!-- Add Modal -->
<div class="modal fade" id="Add_Appointments_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Appointments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onsubmit="return false" method="POST" id="AppointmentsForm" name="AppointmentsForm"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hid" name="hid" value="">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Doctor</label>
                        <div class="col-lg-9">
                            <select name="doctor" class="form-control" id="doctor">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Patient</label>
                        <div class="col-lg-9">
                            <select name="patient" class="form-control" id="patient">
                                <option value="">Select Patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->patient_id }}">{{ $patient->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Date</label>
                        <div class="col-lg-9">
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Time</label>
                        <div class="col-lg-9">
                            <select name="time" id="time" class="form-control">
                                <option value="">Select Time Slot</option>
                            </select>        
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Status</label>
                        <div class="col-lg-9">
                            <select class="select" id="status" name="status">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Modal -->

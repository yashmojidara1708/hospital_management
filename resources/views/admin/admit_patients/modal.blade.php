<!-- Add admit Modal -->
<div class="modal fade" id="add_admit_patient_details" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Admit Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="admitPatientForm" name="admitPatientForm">
                    @csrf
                    <input type="hidden" id="hidden_id" name="hidden_id" value="">

                    <div class="form-group">
                        <label>Patient</label>
                        <select id="patient_id" name="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            @foreach($Allpatients as $patient)
                                <option value="{{ $patient->patient_id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Doctor</label>
                        <select id="doctor_id" name="doctor_id" class="form-control" required>
                            <option value="">Select Doctor</option>
                            @foreach($Alldoctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Room</label>
                        <select id="room_id" name="room_id" class="form-control" required>
                            <option value="">Select Room</option>
                            @foreach($Allrooms as $room)
                            <option value="{{ $room->id }}">{{ $room->room_number }} - {{ $room->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Admission Date</label>
                        <input type="date" id="admission_date" name="admission_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Discharge Date</label>
                        <input type="date" id="discharge_date" name="discharge_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Reason for Admit</label>
                        <textarea id="admit_reason" name="admit_reason" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select id="status" name="status" class="form-control">
                            {{-- <option value=""></option> --}}
                            <option value="1">Admitted</option>
                            <option value="2">Discharged</option>
                            <option value="3">Transferred</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Admit Modal -->

isEditing = false;
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#admitPatientTable')) {
        $('#admitPatientTable').DataTable().destroy();
    }
    $('#room_id').change(function() {
        var roomId = $(this).val();

        if (!roomId || isEditing) {
            return; // Prevent calling function during edit mode
        }

        fetchRoomAvailability(roomId); // Call function only if user manually changes the room
    });


    $('#admitPatientTable').dataTable({
        processing: true,
        serverSide: true,
        searching: true,
        paging: true,
        pageLength: 10,
        destroy: true,

        "ajax": {
            url: "/admin/admit-patient/list",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        order: [
            [0, 'desc']
        ],
        columns: [
            { data: "id" },
            { data: "patient" },
            { data: "doctor" },
            { data: "rooms" },
            { data: "admit_date" },
            { data: "discharge_date" },
            { data: "admission_reason" },
            { data: "status" },
            { data: "action", orderable: false },
        ],
    });

    $("#add_admit_patient_details").on("hidden.bs.modal", function() {
        $("#admitPatientForm")[0].reset();
        $("#hidden_id").val("");
        $("#admitPatientForm").validate().resetForm();
        $("#admitPatientForm").find('.error').removeClass('error');
    });

    $('#add_admit_patient').click(function() {
        $('#modal_title').text('Add Patient');
        $('#admitPatientForm')[0].reset();
        $('#hidden_id').val('');
        $('#add_admit_patient_details').modal('show');
        setTimeout(() => {
            var hiddenId = $("#hidden_id").val(); // Fetch hidden_id after modal is shown
            if (!hiddenId) {
                // If adding a new patient
                $("#discharge_date").prop("disabled", true).val('');
                $("#admission_date").prop("disabled", false);
            }
        }, 500);
    });

    let today = new Date().toISOString().split("T")[0]; // Get today's date
    let pastDate = new Date();
    pastDate.setDate(pastDate.getDate() - 3); // Get past 3 days date
    let minDate = pastDate.toISOString().split("T")[0];

    $("#admission_date").attr("max", today);
    $("#admission_date").attr("min", minDate);

    var validationRules = {
        patient_id: "required",
        room_id: "required",
        admission_date: "required",
        doctor_id: "required",
        status: "required",
    };

    var validationMessages = {
        patient_id: "Please select a patient",
        room_id: "Please select a room",
        admission_date: "Please enter the admission date",
        doctor_id: "Please select a doctor",
        status: "Please select the status",
    };


    $('form[id="admitPatientForm"]').validate({
        rules: validationRules,
        messages: validationMessages,
        submitHandler: function() {
            var formData = new FormData($("#admitPatientForm")[0]);
            $('#loader-container').show();
            $("#discharge_date").prop("disabled", false);
            $("#admission_date").prop("disabled", false);
            $.ajax({
                url: BASE_URL + '/admin/admit-patient/save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if (response.status == 1) {
                        $('#admitPatientForm')[0].reset();
                        $('#hidden_room_id').val('');
                        $('#add_admit_patient_details').modal('hide');
                        $('#admitPatientTable').DataTable().ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        },
    });

    $(document).on("click", "#delete_admitdetails", function() {
        let id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/admin/admit-patient/delete",
                    data: {
                        _token: $("[name='_token']").val(),
                        id: id,
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status == 1) {
                            $('#admitPatientTable').DataTable().ajax.reload();
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
        });
    });

    $(document).on("click", "#edit_admitdetails", function() {
        isEditing = false;
        let id = $(this).data("id");
        let today = new Date().toISOString().split("T")[0]; // Get today's date
        let pastDate = new Date();
        pastDate.setDate(pastDate.getDate() - 3); // Get past 3 days date
        let minDate = pastDate.toISOString().split("T")[0];

        $("#discharge_date").attr("max", today).attr("min", minDate);
        $('#modal_title').text('Edit Patient');
        $.ajax({
            type: "GET",
            url: "/admin/admit-patient/edit",
            data: {
                _token: $("[name='_token']").val(),
                id: id,
            },
            success: function(response) {
                var data = JSON.parse(response);
                console.log("data", data);
                if (data.status == 1) {
                    $('#hidden_id').val(data.data.id);
                    $('#admitPatientForm')[0].reset();
                    $('#admitPatientForm').find('.error').removeClass('error');
                    $('#admitPatientForm').validate().resetForm();
                    $('#add_admit_patient_details').modal('show');
                    $("#admission_date").prop("disabled", true);
                    $("#discharge_date").prop("disabled", false);
                    $("#admitPatientForm").find("#patient_id").val(data.data.patient_id).trigger('change');
                    $("#admitPatientForm").find("#doctor_id").val(data.data.doctor_id).trigger('change');
                    $('#room_id').off('change');

                    $("#admitPatientForm").find("#room_id").val(data.data.room_id).trigger('change');
                    $("#admitPatientForm").find("#admission_date").val(data.data.admit_date);
                    $("#admitPatientForm").find("#discharge_date").val(data.data.discharge_date);
                    $("#admitPatientForm").find("#admit_reason").val(data.data.admission_reason);
                    $("#admitPatientForm").find("#status").val(data.data.status).trigger('change');
                    setTimeout(() => {
                        $('#room_id').on('change', function() {
                            var roomId = $(this).val();
                            if (!roomId || isEditing) {
                                return;
                            }
                            fetchRoomAvailability(roomId);
                        });

                        isEditing = false; // Reset flag after loading form data
                    }, 500);
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });

});

function fetchRoomAvailability(roomId) {
    $.ajax({
        url: '/admin/fetch-room-availability/' + roomId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.length > 0) {
                let room = response[0]; // Assuming one room is returned
                let message = `Room:${room.room_number}-${room.category_name}<br>
                🛏 Total Beds: ${room.beds}<br>  
                🏥 Occupied Beds: ${room.occupied_beds} <br> 
                ✅ Available Beds: ${room.remaining_beds}<br>`;

                let bgColor = room.remaining_beds == 0 ? "error" : "success";

                toastr[bgColor](message, "Room Availability", {
                    timeOut: 15000, // Auto-close after 10 seconds
                    progressBar: true,
                    positionClass: "toast-top-right"
                });
            } else {
                toastr.warning("No data found for this room.", "Warning");
            }
            // Add new events
        },
        error: function(error) {
            toastr.error("Failed to fetch room availability.", "Error");
        }
    });

}
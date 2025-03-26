$(document).ready(function() {
    $("#AppointmentsForm")[0].reset();
    $("#hid").val("");

    $("#Add_Appointments_details").on("hidden.bs.modal", function() {
        $("#AppointmentsForm")[0].reset();
        $("#hid").val("");
        $("#AppointmentsForm").validate().resetForm();
        $("#status").val("").change();
        $("#AppointmentsForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
    });

    if ($.fn.DataTable.isDataTable('#AppointmentsTable')) {
        $('#AppointmentsTable').DataTable().destroy();
    }
    $('#AppointmentsTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/appointmentslist",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        columns: [{
                data: "doctor",
            },
            {
                data: "specialization",
            },

            {
                data: "patient",
            },
            {
                data: "appointment_time",
            },
            {
                data: "status",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });

    $('#loader-container').hide();
})
$(document).on('click', '#Add_Appointments', function() {
    $('#Add_Appointments_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Appointments");
    $("#modal_title").html("Add Appointments");
    $.ajax({
        url: 'appointments/getTimeSlots/',
        type: 'GET',
        data: {
            doctor_id: $("#doctor").val(), // Pass selected doctor ID
            date: $("#date").val() // Pass selected date
        },
        success: function(response) {
            $("#time").html("");
            if (response.timeSlots) {
                $.each(response.timeSlots, function(index, time) {
                    $("#time").append(`<option value="${time}">${time}</option>`);
                });
            }
        }
    });
});
$(document).on('change', '#doctor, #specialization, #date, #time', function() {
    let doctorId = $('#doctor').val();
    let specializationId = $('#specialization').val();
    let patientId = $('#patient').val();
    let selectedDate = $('#date').val();
    let selectedTime = $('#time').val();

    if (doctorId && selectedDate && selectedTime) {
        $.ajax({
            url: 'appointments/checkAvailability',
            type: 'GET',
            data: {
                doctor_id: doctorId,
                specialization_id: specializationId,
                patient_id: patientId,
                date: selectedDate,
                time: selectedTime
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function(xhr) {
                let response = JSON.parse(xhr.responseText);
                toastr.error(response.message);
            }
        });
    }
});


let today = new Date();
today.setDate(today.getDate()); // Set to tomorrow (future date)

let formattedDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD
$("#date").attr("min", formattedDate);

var validationRules = {
    doctor: "required",
    patient: "required",
    date: "required",
    time: "required",
    status: "required",
};

var validationMessages = {
    doctor: "Please enter the doctor's name",
    patient: "Please enter the patient's name",
    date: "Please select the date",
    time: "Please select a time",
    status: "Please select status",

};

$('form[id="AppointmentsForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#AppointmentsForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/appointments/save',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if (data && Object.keys(data).length > 0) {
                    if (data.status == 1) {
                        toastr.success(data.message);
                        $('#loader-container').hide();
                        $('#Add_Appointments_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#loader-container').hide();
                    }
                }
                $("#AppointmentsForm")[0].reset();
                $("#AppointmentsForm").validate().resetForm();
                $("#AppointmentsForm").find('.error').removeClass('error');
                $('#AppointmentsTable').DataTable().ajax.reload();
            }
        });
    },
});
$(document).on('click', '#edit_appointment', function() {
    var id = $(this).data("id");
    $("#time").html("");
    $.ajax({
        type: "GET",
        url: "/admin/appointments/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            console.log("response", response.status);
            if (response.status == 1) {
                if (response.appointments_data) {
                    var appointmentsdata = response.appointments_data;
                    $('#Add_Appointments_details').modal('show');
                    $("#modal_title").html("Edit Appointments");
                    $('#hid').val(appointmentsdata.id);
                    $('#doctor').val(appointmentsdata.doctor);
                    $('#specialization').val(appointmentsdata.specialization);
                    $('#patient').val(appointmentsdata.patient);
                    $('#date').val(appointmentsdata.date);
                    //  $('#time').val(appointmentsdata.time);

                    console.log(appointmentsdata.time);
                    $.ajax({
                        url: 'appointments/getTimeSlots/',
                        type: 'GET',
                        data: {
                            doctor_id: appointmentsdata.doctor,
                            date: appointmentsdata.date,
                            appointment_id: appointmentsdata.id // Pass current appointment ID to allow its slot
                        },
                        success: function(response) {
                            let selectedTime = response.selectedTime.trim(); // Ensure no extra spaces
                            console.log("Selected Time:", selectedTime); // Debugging

                            if (response.timeSlots) {
                                $.each(response.timeSlots, function(index, time) {
                                    var formattedTime = time.trim();
                                    var selected = (formattedTime === selectedTime) ? "selected" : "";
                                    $("#time").append(`<option value="${formattedTime}" ${selected}>${formattedTime}</option>`);
                                });
                            }
                        }
                    });
                    $("#status").val(appointmentsdata.status).change();
                }
            }
        },
    });
});

$(document).on("click", "#delete_appointment", function() {
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
                url: "/admin/appointments/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log("data", data);
                    if (data.status == 1) {
                        $('#AppointmentsTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
$(document).on('change', '.toggle-status', function() {
    var id = $(this).data('id');
    var status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "/admin/appointments/toggle-status",
        type: "POST",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
            status: status
        },
        success: function(response) {
            toastr.success(response.message);
        },
        error: function() {
            toastr.error("Something went wrong!");
        }
    });
});
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on("click", ".mark-complete", function() {
        let appointmentId = $(this).data("id");
    
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to approve this appointment?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Approve!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/doctor/update-appointment-status',
                    type: "POST",
                    data: JSON.stringify({
                        appointmentId: appointmentId,
                        is_completed: 1
                    }),
                    processData: false,
                    contentType: "application/json",
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire("Success!", "Appointment approved successfully.", "success");
                        }
                    },
                    complete: function() {
                        hideLoader();
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        Swal.fire("Failed!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });

    let selectedAppointmentId = null;

    // Reject Appointment Modal Trigger
    $(document).on("click", ".appoinment-delete", function() {
        selectedAppointmentId = $(this).data("id");
        $("#rejectionReason").val(''); // Clear previous reason
        $("#rejectAppointmentModal").modal("show");
    });
    // Confirm Reject Appointment
    $("#confirmRejectAppointment").on("click", function() {
        let reason = $("#rejectionReason").val().trim();
        
        if (!reason) {
            Swal.fire("Error!", "Please provide a rejection reason.", "error");
            return;
        }
    
        $.ajax({
            url: '/doctor/update-appointment-status',
            type: "POST",
            data: JSON.stringify({
                appointmentId: selectedAppointmentId,
                is_completed: -1,
                reason: reason
            }),
            processData: false,
            contentType: "application/json",
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        title: "Success!",
                        text: "Appointment rejected successfully.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire("Error!", response.message || "Something went wrong.", "error");
                }
            },
            complete: function() {
                hideLoader();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                Swal.fire("Failed!", "Something went wrong.", "error");
            }
        });
    
        $("#rejectAppointmentModal").modal("hide");
    });
    console.log('javascript appointment');
    $(document).on('click', '.view-patient-profile', function() {
        let patientId = $(this).data('id'); // Get patient ID from `data-id`
        if (patientId) {
            window.location.href = `/doctor/patientprofile/${patientId}`; // Redirect to Patient Profile page
        } else {
            alert('Invalid patient ID');
        }
    });

    $(document).on('click', '#Add_Appointments', function(e) {
        e.preventDefault();
        var appointmentId = $(this).data('id'); // Get appointment ID
        console.log(appointmentId);
        $.ajax({
            url: "getAppointmentDetails", // Replace with your actual route
            type: "GET",
            data: { id: appointmentId },
            success: function(response) {
                if (response.success) {
                    let appointment = response.data;
                    $('#modal_title').text('Appointment Details #' + appointment.id);
                    let formattedDate = new Date(appointment.date).toLocaleDateString("en-GB", {
                        day: "2-digit",
                        month: "short",
                        year: "numeric"
                    }).replace(" ", " ").replace(",", ",");

                    // Convert time (assuming appointment.time is in "HH:mm:ss" format)
                    let timeParts = appointment.time.split(':'); // Split "HH:mm:ss" to array
                    let hours = parseInt(timeParts[0]); // Get hours
                    let minutes = timeParts[1]; // Get minutes
                    let ampm = hours >= 12 ? 'PM' : 'AM'; // Determine AM/PM

                    // Convert to 12-hour format
                    let formattedTime = (hours % 12 || 12) + ':' + minutes + ' ' + ampm;

                    // Display in span
                    $('#app_date').text(formattedDate + ' ' + formattedTime);

                    // $('#app_date').text(appointment.date + ' ' + appointment.time);
                    if (appointment.status == 1) {
                        $('#topup_status').text("Active");
                        $("#status").text("Active");
                    } else {
                        $('#topup_status').text("Inactive");
                        $("#status").text("Active");
                    }
                    $('#Add_Appointments_details').modal('show'); // Show modal    
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });

    });
});
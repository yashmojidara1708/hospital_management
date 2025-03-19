$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on("click", ".mark-complete", function() {
        let appointmentId = $(this).data("id");
        const requestData = {
            appointmentId: appointmentId,
            is_completed: 1,
        }
        $('#loader-container').show();
        
        $.ajax({
            url: '/doctor/update-appointment-status',
            type: "POST",
            data: JSON.stringify(requestData),
            processData: false,
            contentType: "application/json",
            cache: false,
            beforeSend: function() {
                showLoader();
            },
            
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Appointment completed successfully.",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            complete: function() {
                hideLoader();
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error updating appointment status:", error);
                Swal.fire({
                    icon: "error",
                    title: "Failed!",
                    text: "Something went wrong.",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });

    $(document).on("click", ".appoinment-delete", function() {
        let appointmentId = $(this).data("id");
        const requestData = {
            appointmentId: appointmentId,
            is_completed: -1,
        }
        $.ajax({
            url: '/doctor/update-appointment-status',
            type: "POST",
            data: JSON.stringify(requestData),
            processData: false,
            contentType: "application/json",
            cache: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Appointment Removed successfully.",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            complete: function() {
                hideLoader();
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error updating appointment status:", error);
                Swal.fire({
                    icon: "error",
                    title: "Failed!",
                    text: "Something went wrong.",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
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
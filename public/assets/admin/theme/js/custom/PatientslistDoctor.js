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
        $.ajax({
            url: '/doctor/update-appointment-status',
            type: "POST",
            data: JSON.stringify(requestData),
            processData: false,
            contentType: "application/json",
            cache: false,
            
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
    
                    // Remove row or change appearance
                    fetchUpdatedAppointments();
                }
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
    
                    // Remove row or change appearance
                    fetchUpdatedAppointments();
                }
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
    // Retrieve the active tab from localStorage
    const activeTab = localStorage.getItem('activeTab') || '#pat_appointments'; // Default to appointments tab

    // Activate the stored tab
    $('a[href="' + activeTab + '"]').trigger('click');
    const patientId = $('a[href="' + activeTab + '"]').data('id'); // Get patient ID from data-id

    // Fetch data for the active tab
    if (patientId) {
        if (activeTab === '#pres') {
            fetchprescriptions(patientId); // Fetch prescriptions if the prescription tab is selected
        } else if (activeTab === '#pat_appointments') {
            fetchAppointments(patientId); // Fetch appointments if the appointments tab is selected
        }
    }

    // Handle tab clicks to update localStorage and fetch data
    $('.nav-tabs a').on('click', function(e) {
        const tabHref = $(this).attr('href');
        localStorage.setItem('activeTab', tabHref); // Store the active tab in localStorage

        const patientId = $(this).data('id'); // Get patient ID from data-id
        if (patientId) {
            if (tabHref === '#pres') {
                fetchprescriptions(patientId); // Fetch prescriptions if the prescription tab is clicked
            } else if (tabHref === '#pat_appointments') {
                fetchAppointments(patientId); // Fetch appointments if the appointments tab is clicked
            }
        }
    });

    $(document).on('click', '.view-patient-profile', function() {
        const patientId = $(this).data('id'); // Get patient ID from `data-id`
        if (patientId) {
            window.location.href = `/doctor/patientprofile/${patientId}`; // Redirect to Patient Profile page
        } else {
            alert('Invalid patient ID');
        }
    });

    $("#add-prescription-btn").on("click", function () {
        $('#add-prescription-btn').attr('href', `/doctor/prescription?patient_id=${patientId}`);
    });

    $('a[href="#pat_appointments"]').on('click', function() {
        const patientId = $(this).data('id'); // Get the patient ID from data-id
        console.log(patientId);
        if (patientId) {
            fetchAppointments(patientId); // Call function to fetch appointments
            $('#add-prescription-btn').attr('href', `/doctor/prescription?patient_id=${patientId}`);
        }
    });

    $('a[href="#pres"]').on('click', function() {
        const patientId = $(this).data('id'); // Get the patient ID from data-id
        console.log(patientId);
        if (patientId) {
            fetchprescriptions(patientId); // Call function to fetch prescriptions
        }
    });

    $('a[href="#medical"]').on('click', function() {
        const patientId = $(this).data('id'); // Get the patient ID from data-id
        console.log(patientId);
        if (patientId) {
            // fetchAppointments(patientId); // Call function to fetch appointments
        }
    });

    if ($.fn.DataTable.isDataTable('#PatientsTable')) {
        $('#PatientsTable').DataTable().destroy();
    }
    $('#PatientsTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,
        ajax: {
            url: "patientslist",
            type: 'GET',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        columns: [
            { data: "patient_id" },
            { data: "name" },
            { data: "age" },
            { data: "address" },
            { data: "phone" },
            { data: "email" },
            { data: "last_visit" },
        ],
    });

    function fetchAppointments(patientId) {
        $.ajax({
            url: `/doctor/patientprofile/${patientId}/appointments`, // Laravel route to get appointments
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Appointments Data:', response); // Debugging

                let appointmentList = '';
                if (response.length > 0) {
                    response.forEach(appointment => {
                        const appointmentDate = new Date(appointment.date).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        const lastDate = appointment.last_visit ?
                            new Date(appointment.last_visit).toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }) :
                            'N/A';

                        const status = appointment.status == 1 ?
                            `<span class="badge badge-pill bg-success-light">Active</span>` :
                            `<span class="badge badge-pill bg-danger-light">Inactive</span>`;

                        appointmentList += `
                            <tr>
                                <td>${appointment.name}</td>
                                <td>${appointmentDate}</td>
                                <td>${lastDate}</td>
                                <td>${status}</td>
                            </tr>`;
                    });
                } else {
                    appointmentList = `<tr><td colspan="4" class="text-center">No appointments found.</td></tr>`;
                }

                $('#appointment-list').html(appointmentList);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching appointments:', xhr.responseText);
            }
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB'); // Format: DD/MM/YYYY
    }

    function fetchprescriptions(patientId) {
        $.ajax({
            url: `/doctor/patientprofile/${patientId}/prescriptions`, // Laravel route to get prescriptions
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Prescriptions Data:', response); // Debugging

                let prescriptions = '';
                if (response.length > 0) {
                    response.forEach(prescription => {
                        const medicineNames = prescription.medicine_names ? prescription.medicine_names.join(', ') : 'N/A';
                        const createdAt = formatDate(prescription.created_at);

                        prescriptions += `
                            <tr>
                                <td>${createdAt}</td>
                                <td>${medicineNames}</td>
                                <td>${prescription.doctor_name}</td>
                            </tr>`;
                    });
                } else {
                    prescriptions = `<tr><td colspan="4" class="text-center">No prescriptions found.</td></tr>`;
                }

                $('#prescription-list').html(prescriptions);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching prescriptions:', xhr.responseText);
            }
        });
    }

    // Generic AJAX error handler
    $(document).ajaxError(function(event, xhr, settings, error) {
        console.error('AJAX Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong! Please try again later.',
            confirmButtonColor: '#d33'
        });
    });
});
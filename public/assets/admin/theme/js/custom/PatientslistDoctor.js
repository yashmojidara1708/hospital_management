$(document).ready(function() {
    const hash = window.location.hash;
    if (hash) {
        $('a[href="' + hash + '"]').trigger('click');
        const patientId = $('a[href="' + hash + '"]').data('id');
        if (patientId) {
            if (hash === '#pres') {
                fetchprescriptions(patientId);
            } else if (hash === '#pat_appointments') {
                fetchAppointments(patientId);
            }
        }
    } else {
        $('a[href="#pat_appointments"]').trigger('click');
        const patientId = $('a[href="#pat_appointments"]').data('id');
        if (patientId) {
            fetchAppointments(patientId);
        }
    }

    $('.nav-tabs a').on('click', function(e) {
        window.location.hash = $(this).attr('href');
        const patientId = $(this).data('id');
        if (patientId) {
            if ($(this).attr('href') === '#pres') {
                fetchprescriptions(patientId);
            } else if ($(this).attr('href') === '#pat_appointments') {
                fetchAppointments(patientId);
            }
        }
    });

    $(document).on('click', '.view-patient-profile', function() {
        const patientId = $(this).data('id');
        if (patientId) {
            window.location.href = `/doctor/patientprofile/${patientId}`;
        } else {
            alert('Invalid patient ID');
        }
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
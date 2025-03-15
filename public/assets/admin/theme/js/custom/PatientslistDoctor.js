$(document).ready(function() {
    setTimeout(function() {
        $('a[href="#pat_appointments"]').trigger('click'); // Simulate click instead of .tab('show')
    }, 200);
    $(document).on('click', '.view-patient-profile', function() {
        let patientId = $(this).data('id'); // Get patient ID from `data-id`

        if (patientId) {
            window.location.href = `/doctor/patientprofile/${patientId}`; // Redirect to Patient Profile page
        } else {
            alert('Invalid patient ID');
        }
    });
    $('a[href="#pat_appointments"]').on('click', function() {
        let patientId = $(this).data('id'); // Get the patient ID from data-id
        console.log(patientId);
        if (patientId) {
            fetchAppointments(patientId); // Call function to fetch appointments
        }
    });
    $('a[href="#pres"]').on('click', function() {
        let patientId = $(this).data('id'); // Get the patient ID from data-id
        console.log(patientId);
        if (patientId) {
            // fetchAppointments(patientId); // Call function to fetch appointments
        }
    });
    $('a[href="#medical"]').on('click', function() {
        let patientId = $(this).data('id'); // Get the patient ID from data-id
        console.log(patientId);
        if (patientId) {
            // fetchAppointments(patientId); // Call function to fetch appointments
        }
    });

    $('a[href="#pat_appointments"]').on('click', function() {
        let patientId = $(this).data('id'); // Get the patient ID
        if (patientId) {
            $('#add-prescription-btn').attr('href', `/doctor/prescription?patient_id=${patientId}`);
        }
    });

    if ($.fn.DataTable.isDataTable('#PatientsTable')) {
        $('#PatientsTable').DataTable().destroy();
    }
    $('#PatientsTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "patientslist",
            type: 'GET',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        columns: [{
                data: "patient_id",
            },
            {
                data: "name",
            },
            {
                data: "age",
            },
            {
                data: "address",
            },
            {
                data: "phone",
            },
            {
                data: "email",
            },
            {
                data: "last_visit",
            },
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
                        let appointmentDate = new Date(appointment.date).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        let lastDate = appointment.last_visit ?
                            new Date(appointment.last_visit).toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }) :
                            'N/A';
                        let status = appointment.status == 1 ?
                            `<span class="badge badge-pill bg-success-light">Active</span>` :
                            `<span class="badge badge-pill bg-danger-light">Inactive</span>`;

                        appointmentList += `
                            <tr>
                                <td>${appointment.name}</td>
                                <td>${appointmentDate}</td>
                                <td>${lastDate ?lastDate: 'N/A'}</td>
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
});
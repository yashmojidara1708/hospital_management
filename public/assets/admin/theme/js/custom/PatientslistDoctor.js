$(document).ready(function() {
    $(".row-checkbox").prop("checked", false); 
    initCheckboxFunctionality();
    $("#selectAll").change(function () {
        if ($(this).prop("checked")) {
            $("#bulkActionsContainer").css("display", "flex");
            $(".row-checkbox").prop("checked", true); 
        } else {
            $("#bulkActionsContainer").hide();
            $(".row-checkbox").prop("checked", false); 
        }
    });

    $(document).on("change", ".row-checkbox", function () {
        let totalCheckboxes = $(".row-checkbox").length;
        let checkedCheckboxes = $(".row-checkbox:checked").length;

        if (checkedCheckboxes > 0) {
            $("#bulkActionsContainer").show();
        } else {
            $("#bulkActionsContainer").hide();
        }

        $("#selectAll").prop("checked", checkedCheckboxes === totalCheckboxes);
    });

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
        $("#rejectionReason").val(''); 
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
                        $(".row-checkbox").prop("checked", false); 
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
    // Retrieve the active tab from localStorage
    const activeTab = localStorage.getItem('activeTab') || '#pat_appointments'; // Default to appointments tab

    // Activate the stored tab
    $('a[href="' + activeTab + '"]').trigger('click');
    // const patientId = $('a[href="' + activeTab + '"]').data('id'); // Get patient ID from data-id
    let patientId = $('.nav-tabs .nav-link.active').data("id");

    // Fetch data for the active tab
    if (patientId) {
        if (activeTab === '#pres') {
            fetchprescriptions(patientId); // Fetch prescriptions if the prescription tab is selected
        } else if (activeTab === '#pat_appointments') {
            fetchAppointments(patientId); // Fetch appointments if the appointments tab is selected
        }
    }
    if (patientId) {
        fetchAppointments(patientId);
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

    let selectedAppointments = [];

    // Checkbox functionality
    function initCheckboxFunctionality() {
        // Select All checkbox change
        $('#selectAll').change(function() {
            const isChecked = $(this).prop('checked');
            $('.row-checkbox').prop('checked', isChecked);
            updateSelectedAppointments();
        });
        
        // Individual row checkbox change
        $(document).on('change', '.row-checkbox', function() {
            // Update Select All checkbox status
            const allChecked = $('.row-checkbox:checked').length === $('.row-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
            
            updateSelectedAppointments();
        });
    }
    

    // Update selected appointments array
    function updateSelectedAppointments() {
        selectedAppointments = $(".row-checkbox:checked").map(function () {
            return $(this).attr("data-id"); // FIX: Use attr() instead of data()
        }).get();
        
        // Show/hide bulk actions
        if (selectedAppointments.length > 0) {
            $('#bulkActionsContainer').show();
            $('#selectedAppointments').val(JSON.stringify(selectedAppointments));
        } else {
            $('#bulkActionsContainer').hide();
        }
        
        // Update select all checkbox
        $('#selectAll').prop('checked', 
            selectedAppointments.length === $('.row-checkbox').length
        );
    }

    // Bulk Approve
    $('#bulkApprove').click(function() {
        if (selectedAppointments.length === 0) return;
        
        Swal.fire({
            title: "Approve Selected Appointments?",
            text: `You are about to approve ${selectedAppointments.length} appointment(s)`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Approve!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                updateBulkAppointments('approve');
            }
        });
    });

    // Bulk Reject
    // $('#bulkReject').click(function() {
    //     if (selectedAppointments.length === 0) return;
        
    //     Swal.fire({
    //         title: "Reject Selected Appointments?",
    //         text: `You are about to reject ${selectedAppointments.length} appointment(s)`,
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonText: "Yes, Reject!",
    //         cancelButtonText: "Cancel"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             updateBulkAppointments('reject');
    //         }
    //     });
    // });
    $("#confirmBulkRejectAppointment").click(function () {
        let reason = $("#bulkRejectionReasonTextarea").val().trim();
        if (!reason) {
            Swal.fire("Error!", "Please provide a rejection reason.", "error");
            return;
        }

        updateBulkAppointments("reject");
        $("#rejectBulkAppointmentModal").modal("hide");
    });
    $("#customReasonCheckbox").change(function () {
        if ($(this).is(":checked")) {
            $("#bulkRejectionReasonTextarea").show();
        } else {
            $("#bulkRejectionReasonTextarea").hide().val("");
        }
    });
    $('#bulkReject').click(function() {
        // selectedAppointments = $(".row-checkbox:checked").map(function () {
        //     return $(this).val();
        // }).get();

        // if (selectedAppointments.length === 0) {
        //     alert("Please select at least one appointment.");
        //     return;
        // }

        // Open modal
        $("#customReasonCheckbox").prop("checked", false);
        $("#bulkRejectionReasonTextarea").hide().val(""); // Hide textarea initially
        $("#rejectBulkAppointmentModal").modal("show");
    });
    

    // Update bulk appointments status
    function updateBulkAppointments(action) {
        const status = action === 'approve' ? 1 : -1;

        $.ajax({
            url: '/doctor/update-all-appointments-status',
            type: "POST",
            data: JSON.stringify({
                appointmentIds: selectedAppointments,
                status: status
            }),
            processData: false,
            contentType: "application/json",
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                if (response.status === "success") {
                    const message = action === 'approve' 
                        ? "Appointments approved successfully" 
                        : "Appointments rejected successfully";
                    
                    Swal.fire({
                        title: "Success!",
                        text: message,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        fetchUpdatedAppointments();
                    });
                }
            },
            complete: function() {
                hideLoader();
                $('#bulkActionsContainer').hide();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                Swal.fire("Failed!", "Something went wrong.", "error");
            }
        });
    }

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
            url: `/doctor/patientprofile/${patientId}/prescriptions`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let prescriptions = '';
                if (response.length > 0) {
                    response.forEach(prescription => {
                        const medicineNames = prescription.medicine_names ? prescription.medicine_names.join(', ') : 'N/A';
                        const createdAt = formatDate(prescription.created_at);
                        const invoiceUrl = `/doctor/invoice/${prescription.id}`;
                        prescriptions += `
                            <tr>
                                <td>${createdAt}</td>
                                <td>${medicineNames}</td>
                                <td>${prescription.doctor_name}</td>
                                <td class="text-center">
                                    <div class="table-action">
                                        <a href="javascript:void(0);" class="btn btn-sm bg-success-light edit-prescription" 
                                            data-id="${prescription.id}" 
                                            data-medicines='${JSON.stringify(prescription.medicine_names)}'>
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-sm bg-danger-light delete-prescription" 
                                            data-id="${prescription.id}">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <a href="${invoiceUrl}" class="btn btn-sm bg-primary-light">
                                            <i class="fas fa-file-invoice"></i> View Invoice
                                        </a>
                                    </div>
                                </td>
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

    $(document).on('click', '.delete-prescription', function() {
        let prescriptionId = $(this).data('id');
    
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/doctor/prescriptions/${prescriptionId}/delete`,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`#prescription-${prescriptionId}`).remove();
                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: "Prescription has been deleted successfully.",
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            fetchprescriptions();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops!",
                                text: "Failed to delete prescription.",
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error deleting prescription:', xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Something went wrong. Please try again later.",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.edit-prescription', function() {
        let prescriptionId = $(this).data('id');
        console.log("prescriptionId:", prescriptionId)
        window.location.href = 
                    `/doctor/prescription?patient_id=${patientId}&prescription_id=${prescriptionId}`;
        // $.ajax({
        //     url: `/doctor/prescription/${prescriptionId}/edit`,
        //     method: 'GET',
        //     dataType: 'json',
        //     success: function(response) {
        //         if (response.success) {
        //             window.location.href = 
        //             `/doctor/prescription?patient_id=${patientId}&prescription_id=${prescriptionId}`;
        //         }
        //     },
        //     error: function(xhr) {
        //         console.error("Error fetching prescription:", xhr.responseText);
        //     }
        // });
    });
    
    // Generic AJAX error handler
    // $(document).ajaxError(function(event, xhr, settings, error) {
    //     console.error('AJAX Error:', error);
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Oops...',
    //         text: 'Something went wrong! Please try again later.',
    //         confirmButtonColor: '#d33'
    //     });
    // });
});
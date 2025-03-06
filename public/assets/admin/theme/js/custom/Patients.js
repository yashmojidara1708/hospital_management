$(document).ready(function() {
    $("#PatientsForm")[0].reset();
    $("#hid").val("");

    $("#Add_Patients_details").on("hidden.bs.modal", function() {
        $("#PatientsForm")[0].reset();
        $("#hid").val("");
        $("#PatientsForm").validate().resetForm();
        $("#status").val("").change();
        $("#PatientsForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
    });

    if ($.fn.DataTable.isDataTable('#PatientsTable')) {
        $('#PatientsTable').DataTable().destroy();
    }

    $('#PatientsTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/patientslist",
            type: 'POST',
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
            {
                data: "paid",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });
    $('#loader-container').hide();
})

$(document).on('click', '#Add_Patients', function() {
    $('#Add_Patients_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Patients");
    $("#modal_title").html("Add Patients");
});

var validationRules = {
    name: "required",
    age: "required",
    address: "required",
    country: "required",
    city: "required",
    state: "required",
    zip: {
        required: true,
        maxlength: 6,
        minlength: 5,
        digits: true, // Ensures only numbers
    },
    phone: {
        required: true,
        digits: true, // Ensures only numbers
        minlength: 10,
        maxlength: 10,
    },
    email: {
        required: true,
        email: true,
    },
    last_visit: "required",
    paid: {
        required: true,
        digits: true, // Ensures only numbers
        minlength: 3,
        maxlength: 5,
    },
};

var validationMessages = {
    name: "Please enter the patient's name",
    age: "Please enter the patient's age",
    address: "Please enter the address",
    country: "Please select a country",
    city: "Please enter the city",
    state: "Please enter the state",
    zip: {
        required: "Please enter the ZIP code",
        maxlength: "ZIP code cannot be more than 6 digits",
        minlength: "ZIP code must be at least 5 digits",
        digits: "ZIP code must contain only numbers",
    },
    phone: {
        required: "Please enter the phone number",
        digits: "Phone number must contain only numbers",
        minlength: "Phone number must be exactly 10 digits",
        maxlength: "Phone number must be exactly 10 digits",
    },
    email: {
        required: "Please enter the email address",
        email: "Please enter a valid email address",
    },
    last_visit: "Please select the last visit date",
    paid: {
        required: "Please enter the Paid Amount",
        maxlength: "Paid amount cannot be more than 5 digits",
        minlength: "Paid amount must be at least 3 digits",
        digits: "Paid amount must contain only numbers",
    },
};
let yesterday = new Date();
yesterday.setDate(yesterday.getDate() - 1); // Set to previous date

let formattedDate = yesterday.toISOString().split('T')[0]; // Format as YYYY-MM-DD
$("#last_visit").val(formattedDate); // Set default value
$("#last_visit").attr("max", formattedDate);

$('form[id="PatientsForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#PatientsForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/patients/save',
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
                        $('#Add_Patients_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#loader-container').hide();
                    }
                }
                $("#PatientsForm")[0].reset();
                $("#PatientsForm").validate().resetForm();
                $("#PatientsForm").find('.error').removeClass('error');
                $('#PatientsTable').DataTable().ajax.reload();
                $('#per_details_tab').load(window.location.href + ' #per_details_tab');
            }
        });
    },
});

$(document).on("click", "#delete_patients", function() {
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
                url: "/admin/patients/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == 1) {
                        $('#PatientsTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
$(document).on('click', '#patientsEdit', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/patients/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            if (response.status == 1) {
                if (response.patients_data) {
                    var patientsdata = response.patients_data;
                    $('#Add_Patients_details').modal('show');
                    $("#modal_title").html("Edit patients");
                    $('#hid').val(patientsdata.patient_id);
                    $('#name').val(patientsdata.name);
                    $('#age').val(patientsdata.age);
                    $('#address').val(patientsdata.address);
                    $('#phone').val(patientsdata.phone);
                    $('#email').val(patientsdata.email);
                    $('#last_visit').val(patientsdata.last_visit);
                    $('#paid').val(patientsdata.paid);
                    $('#city').val(patientsdata.city);
                    $('#state').val(patientsdata.state);
                    $("#zip").val(patientsdata.zip);
                    $("#country").val(patientsdata.country).change();
                }
            }
        },
    });
});
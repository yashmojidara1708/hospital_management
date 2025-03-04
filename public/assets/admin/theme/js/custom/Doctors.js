$(document).ready(function() {
    $('#image').change(function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            $('#imagePreview').attr('src', reader.result).show();
        };
        reader.readAsDataURL(event.target.files[0]); // Read the selected file
    });

    $("#DoctorsForm")[0].reset();
    $("#hid").val("");

    $("#Add_Doctors_details").on("hidden.bs.modal", function() {
        $("#DoctorsForm")[0].reset();
        $("#hid").val("");
        $("#DoctorsForm").validate().resetForm();
        $("#status").val("").change();
        $("#DoctorsForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
    });

    if ($.fn.DataTable.isDataTable('#DoctorsTable')) {
        $('#DoctorsTable').DataTable().destroy();
    }
    $('#DoctorsTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/doctorslist",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        columns: [{
                data: "image",
            },
            {
                data: "name",
            },
            {
                data: "specialization",
            },

            {
                data: "phone",
            },
            {
                data: "email",
            },
            {
                data: "experience",
            },
            {
                data: "qualification",
            },
            {
                data: "address",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });

    $('#loader-container').hide();
})

$(document).on('click', '#Add_Doctors', function() {
    $('#Add_Doctors_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Doctors");
    $("#modal_title").html("Add Doctors");
});
var validationRules = {
    name: "required",
    specialization: "required",
    phone: "required",
    email: {
        required: true,
        email: true,
    },
    experience: "required",
    qualification: "required",
    address: "required",
    country: "required",
    city: "required",
    state: "required",
    zip: "required",

};

var validationMessages = {
    name: "Please enter the doctor's name",
    specialization: "Please select the doctor's specialization",
    phone: "Please enter the phone number",
    email: {
        required: "Please enter the email address",
        email: "Please enter a valid email address",
    },
    experience: "Please enter the experience in years",
    qualification: "Please enter the qualification",
    address: "Please enter the address",
    country: "Please select a country",
    city: "Please enter the city",
    state: "Please enter the state",
    zip: "Please enter the ZIP code",
};

$('form[id="DoctorsForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#DoctorsForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/doctors/save',
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
                        $('#Add_Doctors_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#loader-container').hide();
                    }
                }
                $("#DoctorsForm")[0].reset();
                $("#DoctorsForm").validate().resetForm();
                $("#DoctorsForm").find('.error').removeClass('error');
                $('#DoctorsTable').DataTable().ajax.reload();
            }
        });
    },
});
$(document).on('click', '#doctorsEdit', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/doctors/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            if (response.status == 1) {
                if (response.doctors_data) {
                    var doctorsdata = response.doctors_data;
                    $('#Add_Patients_details').modal('show');
                    $("#modal_title").html("Edit Doctor");
                    $('#hid').val(doctorsdata.id);
                    $('#name').val(doctorsdata.name);
                    $('#specialization').val(doctorsdata.specialization);
                    $('#phone').val(doctorsdata.phone);
                    $('#email').val(doctorsdata.email);
                    $('#experience').val(doctorsdata.experience);
                    $('#qualification').val(doctorsdata.qualification);
                    $('#address').val(doctorsdata.address);
                    $('#city').val(doctorsdata.city);
                    $('#state').val(doctorsdata.state);
                    $("#zip").val(doctorsdata.zip);
                    $("#country").val(doctorsdata.country).change();
                }
            }
        },
    });
});
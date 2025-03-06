$(document).ready(function() {
    $("#DoctorsForm")[0].reset();
    $("#hid").val("");
    $("#priview_image_title").hide();
    $("#close_icone").hide();
    $("#oldimgbox").hide();
    image.onchange = evt => {
        const [file] = image.files
        if (file) {
            $("#img_privew").show();
            $("#priview_image_title").show();
            $("#close_icone").show();
            img_privew.src = URL.createObjectURL(file)
        }
    }

    $("#close_icone").click(function() {
        $("#img_privew").attr('src', '#').hide();
        $("#priview_image_title").hide();
        $("#image").val(''); // Clear file input
    });

    $('#country').on('change', function() {
        var countryId = $(this).val();
        $('#state').html('<option value="">Loading...</option>'); // Show loading text
        $('#city').html('<option value="">Select City</option>'); // Reset city

        if (countryId) {
            $.ajax({
                url: 'get-states/' + countryId,
                type: 'GET',
                dataType: 'json',
                success: function(states) {
                    $('#state').html('<option value="">Select State</option>');
                    $.each(states, function(index, state) {
                        $('#state').append('<option value="' + state.id + '">' + state.name + '</option>');
                    });
                }
            });
        } else {
            $('#state').html('<option value="">Select State</option>'); // Reset state if no country selected
        }
    });

    // When State is changed, fetch cities
    $('#state').on('change', function() {
        var stateId = $(this).val();
        $('#city').html('<option value="">Loading...</option>'); // Show loading text

        if (stateId) {
            $.ajax({
                url: 'get-cities/' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(cities) {
                    $('#city').html('<option value="">Select City</option>');
                    $.each(cities, function(index, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                }
            });
        } else {
            $('#city').html('<option value="">Select City</option>'); // Reset city if no state selected
        }
    });

    $("#Add_Doctors_details").on("hidden.bs.modal", function() {
        $("#DoctorsForm")[0].reset();
        $("#hid").val("");
        $("#DoctorsForm").validate().resetForm();
        $("#status").val("").change();
        $("#DoctorsForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
        $('.password-container').show();
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
    phone: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 10,
    },
    email: {
        required: true,
        email: true,
    },
    experience: {
        required: true,
        digits: true,
        maxlength: 2,
    },
    qualification: "required",
    address: "required",
    country: "required",
    city: "required",
    state: "required",
    zip: {
        required: true,
        digits: true,
        minlength: 6,
        maxlength: 6,
    },
    password: {
        required: function() {
            return $('#hid').val() === "";
        },

        minlength: 8,
        pattern: /^(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/,
    },

};

var validationMessages = {
    name: "Please enter the doctor's name",
    specialization: "Please select the doctor's specialization",
    phone: {
        required: "Please enter the phone number",
        digits: "Please enter only digits for the phone number",
        minlength: "Phone number must be exactly 10 digits",
        maxlength: "Phone number must be exactly 10 digits",
    },
    email: {
        required: "Please enter the email address",
        email: "Please enter a valid email address",
    },
    experience: {
        required: "Please enter the experience in years",
        digits: "Experience must be a valid number",
        maxlength: "Experience must be a maximum of 2 digits",
    },
    qualification: "Please enter the qualification",
    address: "Please enter the address",
    country: "Please select a country",
    city: "Please enter the city",
    state: "Please enter the state",
    zip: {
        required: "Please enter the ZIP code",
        digits: "ZIP code must be numeric",
        minlength: "ZIP code must be exactly 6 digits",
        maxlength: "ZIP code must be exactly 6 digits",
    },
    password: {
        required: "Please enter a password",
        minlength: "Password must be at least 8 characters long",
        pattern: "Password must contain at least one special character",
    },
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

$(document).on("click", "#delete_doctors", function() {
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
                url: "/admin/doctors/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == 1) {
                        $('#DoctorsTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});

$(document).on('click', '#edit_doctors', function() {
    var id = $(this).data("id");
    console.log("id", id);
    $.ajax({
        type: "GET",
        url: "/admin/doctors/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            if (response.status == 1) {
                if (response.doctor_data) {
                    var doctorsdata = response.doctor_data;
                    console.log("doctorsdata", doctorsdata);
                    $('#Add_Doctors_details').modal('show');
                    $("#modal_title").html("Edit Doctor");
                    $('#hid').val(doctorsdata.id);
                    $('#name').val(doctorsdata.name);
                    $('#specialization').val(doctorsdata.specialization);
                    $('#phone').val(doctorsdata.phone);
                    $('#email').val(doctorsdata.email);
                    $('#experience').val(doctorsdata.experience);
                    $('#qualification').val(doctorsdata.qualification);
                    $('#address').val(doctorsdata.address);
                    // $('#city').val(doctorsdata.city);
                    // $('#state').val(doctorsdata.state);
                    // $("#country").val(doctorsdata.country).change();
                    $("#zip").val(doctorsdata.zip);
                    $("#image").attr("required", false);
                    console.log("doctorsdata.image", doctorsdata);
                    if (doctorsdata.image != "") {
                        $("#oldimgbox").show();
                        $("#imgbox").html(doctorsdata.image);
                    }
                    $('.password-container').hide();

                    $('#country').val(doctorsdata.country).change();

                    // Load states based on selected country
                    $.ajax({
                        url: 'get-states/' + doctorsdata.country,
                        type: 'GET',
                        dataType: 'json',
                        success: function(states) {
                            $('#state').html('<option value="">Select State</option>');
                            $.each(states, function(index, state) {
                                $('#state').append('<option value="' + state.id + '">' + state.name + '</option>');
                            });

                            // Set the state value and trigger change to load cities
                            $('#state').val(doctorsdata.state).change();

                            // Load cities based on selected state
                            $.ajax({
                                url: 'get-cities/' + doctorsdata.state,
                                type: 'GET',
                                dataType: 'json',
                                success: function(cities) {
                                    $('#city').html('<option value="">Select City</option>');
                                    $.each(cities, function(index, city) {
                                        $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                                    });

                                    // Set the city value once the cities are loaded
                                    $('#city').val(doctorsdata.city);
                                }
                            });
                        }
                    });
                }
            }
        },
    });
});
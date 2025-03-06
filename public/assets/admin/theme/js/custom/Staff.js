$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#StaffForm")[0].reset();
    $("#hid").val("");
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



    $("#Add_Staff_details").on("hidden.bs.modal", function() {
        $("#StaffForm")[0].reset();
        $("#hid").val("");
        $("#StaffForm").validate().resetForm();
        $("#status").val("").change();
        $("#StaffForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
        $('.password-container').show();
    });

    if ($.fn.DataTable.isDataTable('#StaffTable')) {
        $('#StaffTable').DataTable().destroy();
    }

    $('#StaffTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/stafflist",
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
                data: "roles",
            },
            {
                data: "email",
            },
            {
                data: "phone",
            },
            {
                data: "address",
            },
            {
                data: "date_of_birth",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });
    $('#loader-container').hide();
})

$(document).on('click', '#Add_Staff', function() {
    $('#Add_Staff_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Staff");
    $("#modal_title").html("Add Staff");
});


var validationRules = {
    name: "required",
    "roles[]": {
        required: true
    },
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
    password: {
        required: function() {
            return $('#hid').val() === "";
        },

        minlength: 8,
        pattern: /^(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/,
    },
    date_of_birth: "required",
};

var validationMessages = {
    name: "Please enter the staff name",
    "roles[]": "Please select at least one role",
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
    password: {
        required: "Please enter a password",
        minlength: "Password must be at least 8 characters long",
        pattern: "Password must contain at least one special character",
    },
    date_of_birth: "Please select the birth date",
};
let yesterday = new Date();
yesterday.setDate(yesterday.getDate() - 1); // Set to previous date

let formattedDate = yesterday.toISOString().split('T')[0]; // Format as YYYY-MM-DD
$("#date_of_birth").val(formattedDate); // Set default value
$("#date_of_birth").attr("max", formattedDate);

$('form[id="StaffForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    errorPlacement: function(error, element) {
        if (element.attr("name") === "roles[]") {
            error.appendTo("#role-error");
        } else {
            error.insertAfter(element);
        }
    },

    submitHandler: function() {
        var formData = new FormData($("#StaffForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/staff/save',
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
                        $('#Add_Staff_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#loader-container').hide();
                    }
                }
                $("#StaffForm")[0].reset();
                $("#StaffForm").validate().resetForm();
                $("#StaffForm").find('.error').removeClass('error');
                $('#StaffTable').DataTable().ajax.reload();
                $('#per_details_tab').load(window.location.href + ' #per_details_tab');
            }
        });
    },
});

$(document).on('click', '#edit_staff', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/staff/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            if (response.status == 1) {
                if (response.staffs_data) {
                    var staffsdata = response.staffs_data;
                    $('#Add_Staff_details').modal('show');
                    $("#modal_title").html("Edit Staff");

                    // Set form values
                    $('#hid').val(staffsdata.id);
                    $('#name').val(staffsdata.name);
                    $('#address').val(staffsdata.address);
                    $('#phone').val(staffsdata.phone);
                    $('#email').val(staffsdata.email);
                    $("#zip").val(staffsdata.zip);
                    $('#date_of_birth').val(staffsdata.date_of_birth);
                    $('.password-container').hide();

                    // Load and set country, state, and city
                    $('#country').val(staffsdata.country).change();

                    // Load states based on selected country
                    // Load and set country, state, and city
                    $('#country').val(staffsdata.country).change();

                    // Load states based on selected country
                    $.ajax({
                        url: 'get-states/' + staffsdata.country,
                        type: 'GET',
                        dataType: 'json',
                        success: function(states) {
                            $('#state').html('<option value="">Select State</option>');
                            $.each(states, function(index, state) {
                                $('#state').append('<option value="' + state.id + '">' + state.name + '</option>');
                            });

                            // Set the state value and trigger change to load cities
                            $('#state').val(staffsdata.state).change();

                            // Load cities based on selected state
                            $.ajax({
                                url: 'get-cities/' + staffsdata.state,
                                type: 'GET',
                                dataType: 'json',
                                success: function(cities) {
                                    $('#city').html('<option value="">Select City</option>');
                                    $.each(cities, function(index, city) {
                                        $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                                    });

                                    // Set the city value once the cities are loaded
                                    $('#city').val(staffsdata.city);
                                }
                            });
                        }
                    });
                    // Set roles checkboxes
                    $('input[type="checkbox"][name="roles[]"]').prop('checked', false);
                    $.each(staffsdata.roles, function(key, value) {
                        $('input[type="checkbox"][name="roles[]"][value="' + value + '"]').prop('checked', true);
                    });
                }
            }
        },
    });
});


$(document).on("click", "#delete_staff", function() {
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
                url: "/admin/staff/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == 1) {
                        $('#StaffTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
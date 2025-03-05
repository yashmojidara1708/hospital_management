$(document).ready(function() {
    $("#StaffForm")[0].reset();
    $("#hid").val("");

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
    zip: "required",
    phone: "required",
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
    zip: "Please enter the ZIP code",
    phone: "Please enter the phone number",
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
                    $('#hid').val(staffsdata.id);
                    $('#name').val(staffsdata.name);
                    $('#address').val(staffsdata.address);
                    $('#phone').val(staffsdata.phone);
                    $('#email').val(staffsdata.email);
                    $('#city').val(staffsdata.city);
                    $('#state').val(staffsdata.state);
                    $("#zip").val(staffsdata.zip);
                    $("#country").val(staffsdata.country).change();
                    $('input[type="checkbox"][name="roles[]"]').prop('checked', false);
                    $.each(staffsdata.roles, function(key, value) {
                        $('input[type="checkbox"][name="roles[]"][value="' + value + '"]').prop('checked', true);
                    });
                    $('#date_of_birth').val(staffsdata.date_of_birth);
                    $('.password-container').hide();
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
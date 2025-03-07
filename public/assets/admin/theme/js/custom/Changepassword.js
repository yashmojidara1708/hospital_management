$(document).ready(function() {

    $.ajax({
        url: "getProfile",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (response.status === 1) {
                $('#profileName').text(response.data.name);
                $('#profileEmail').text(response.data.email);
                $('#profileAddress').text(response.data.address);
                $('#profilePhone').text(response.data.phone);
                $('#profileCity').text(response.data.city);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert("Error fetching profile data.");
        }
    });

    var validationRules = {
        oldpassword: "required",
        newpassword: {
            required: true,
            minlength: 8
        },
        confirmpassword: {
            required: true,
            equalTo: "#newpassword"
        }
    };

    var validationMessages = {
        oldpassword: "This field is required",
        newpassword: {
            required: "This field is required",
            minlength: "Password must be at least 8 characters long"
        },
        confirmpassword: {
            required: "This field is required",
            equalTo: "Confirm password must match the new password"
        }
    };

    $('form[id="changepasswordForm"]').validate({
        rules: validationRules,
        messages: validationMessages,
        submitHandler: function() {
            var formData = new FormData($("#changepasswordForm")[0]);
            $('#loader-container').show();
            $.ajax({
                url: 'updatepassword',
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

                        } else {
                            toastr.error(data.message);
                            $('#loader-container').hide();
                        }
                    }
                    $("#changepasswordForm")[0].reset();
                    $("#changepasswordForm").validate().resetForm();
                    $("#changepasswordForm").find('.error').removeClass('error');
                }
            });
        },
    });
});
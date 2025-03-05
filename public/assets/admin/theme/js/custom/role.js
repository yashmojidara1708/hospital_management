$(document).ready(function() {
    $("#roleForm")[0].reset();
    $("#hid").val("");

    $("#Add_Role_details").on("hidden.bs.modal", function() {
        $("#roleForm")[0].reset();
        $("#hid").val("");
        $("#roleForm").validate().resetForm();
        $("#status").val("").change();
        $("#roleForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
    });

    if ($.fn.DataTable.isDataTable('#roleTable')) {
        $('#roleTable').DataTable().destroy();
    }

    $('#roleTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/rolelist",
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
                data: "status",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });
    $('#loader-container').hide();
})

$(document).on('click', '#Add_Role', function() {
    $('#Add_Role_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Role");
    $("#modal_title").html("Add Role");
});
var validationRules = {
    name: "required",
};

var validationMessages = {
    name: 'This field is required',
};

$('form[id="roleForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#roleForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/role/save',
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
                        $('#Add_Role_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#loader-container').hide();
                    }
                }
                $("#roleForm")[0].reset();
                $("#roleForm").validate().resetForm();
                $("#roleForm").find('.error').removeClass('error');
                $('#roleTable').DataTable().ajax.reload();
            }
        });
    },
});
$(document).on('click', '#edit_role', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/role/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            console.log("response", response.status);
            if (response.status == 1) {
                if (response.role_data) {
                    var roledata = response.role_data;
                    $('#Add_Role_details').modal('show');
                    $("#modal_title").html("Edit Role");
                    $('#hid').val(roledata.id);
                    $('#name').val(roledata.name);
                    // $("#status").val(specialitiesdata.status);
                    $("#status").val(roledata.status).change();
                }
            }
        },
    });
});
$(document).on("click", "#delete_role", function() {
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
                url: "/admin/role/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log("data", data);
                    if (data.status == 1) {
                        $('#roleTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
$(document).on('change', '.toggle-status', function() {
    var id = $(this).data('id');
    var status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "/admin/role/toggle-status",
        type: "POST",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
            status: status
        },
        success: function(response) {
            toastr.success(response.message);
        },
        error: function() {
            toastr.error("Something went wrong!");
        }
    });
});
$(document).ready(function() {
    $("#roomCategoryForm")[0].reset();
    $("#hid").val("");

    $("#Add_Room_category").on("hidden.bs.modal", function() {
        $("#roomCategoryForm")[0].reset();
        $("#hid").val("");
        $("#roomCategoryForm").validate().resetForm();
        $("#roomCategoryForm").find('.error').removeClass('error');
    });

    if ($.fn.DataTable.isDataTable('#roomCategoryTable')) {
        $('#roomCategoryTable').DataTable().destroy();
    }

    $('#roomCategoryTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/roomcategorylist",
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
                data: "action",
                orderable: false
            },
        ],
    });
    $('#loader-container').hide();
})

$(document).on('click', '#Add_Room', function() {
    $('#Add_Room_category').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Room Category");
    $("#modal_title").html("Add Room Category");
});
var validationRules = {
    name: "required",
};

var validationMessages = {
    name: 'This field is required',
};

$('form[id="roomCategoryForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#roomCategoryForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/roomCategory/save',
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
                        $('#Add_Room_category').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#Add_Room_category').modal('hide');
                        $('#loader-container').hide();
                    }
                }
                $("#roomCategoryForm")[0].reset();
                $("#roomCategoryForm").validate().resetForm();
                $("#roomCategoryForm").find('.error').removeClass('error');
                $('#roomCategoryTable').DataTable().ajax.reload();
            }
        });
    },
});
$(document).on('click', '#edit_role', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/roomCategory/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            console.log("response", response.status);
            if (response.status == 1) {
                if (response.role_data) {
                    var roledata = response.role_data;
                    $('#Add_Room_category').modal('show');
                    $("#modal_title").html("Edit Room Category");
                    $('#hid').val(roledata.id);
                    $('#name').val(roledata.name);
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
                url: "/admin/roomCategory/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log("data", data);
                    if (data.status == 1) {
                        $('#roomCategoryTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
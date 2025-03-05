$(document).ready(function() {
    $("#specialitiesForm")[0].reset();
    $("#hid").val("");

    $("#Add_Specialities_details").on("hidden.bs.modal", function() {
        $("#specialitiesForm")[0].reset();
        $("#hid").val("");
        $("#specialitiesForm").validate().resetForm();
        $("#status").val("").change();
        $("#specialitiesForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
    });

    if ($.fn.DataTable.isDataTable('#specialitiesTable')) {
        $('#specialitiesTable').DataTable().destroy();
    }

    $('#specialitiesTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/specialitieslist",
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

$(document).on('click', '#Add_Specialities', function() {
    $('#Add_Specialities_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Specialities");
    $("#modal_title").html("Add Specialities");
});

var validationRules = {
    name: "required",
};

var validationMessages = {
    name: 'This field is required',
};

$('form[id="specialitiesForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#specialitiesForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/specialities/save',
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
                        $('#Add_Specialities_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#loader-container').hide();
                    }
                }
                $("#specialitiesForm")[0].reset();
                $("#specialitiesForm").validate().resetForm();
                $("#specialitiesForm").find('.error').removeClass('error');
                $('#specialitiesTable').DataTable().ajax.reload();
            }
        });
    },
});
$(document).on('change', '.toggle-status', function() {
    var id = $(this).data('id');
    var status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "/admin/specialities/toggle-status",
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
$(document).on('click', '#specialitiesEdit', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/specialities/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            console.log("response", response.status);
            if (response.status == 1) {
                if (response.specialities_data) {
                    var specialitiesdata = response.specialities_data;
                    $('#Add_Specialities_details').modal('show');
                    $("#modal_title").html("Edit Specialities");
                    $('#hid').val(specialitiesdata.id);
                    $('#name').val(specialitiesdata.name);
                    // $("#status").val(specialitiesdata.status);
                    $("#status").val(specialitiesdata.status).change();
                }
            }
        },
    });
});

$(document).on("click", "#delete_specialities", function() {
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
                url: "/admin/specialities/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log("data", data);
                    if (data.status == 1) {
                        $('#specialitiesTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
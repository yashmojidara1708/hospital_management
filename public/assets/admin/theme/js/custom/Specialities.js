$(document).ready(function() {
    $("#specialitiesForm")[0].reset();
    $("#hid").val("");

    $("#Add_Specialities_details").on("hidden.bs.modal", function() {
        $("#specialitiesForm")[0].reset();
        $("#hid").val("");
        $("#specialitiesForm").validate().resetForm();
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
                data: "id",
            },
            {
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
})

$(document).on('click', '#Add_Specialities', function() {
    $('#Add_Specialities_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Team");
    $("#modal_title").html("Add Team");
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
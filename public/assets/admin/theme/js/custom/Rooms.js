$(document).ready(function() {
    $("#roomForm")[0].reset();
    $("#hid").val("");

    $("#Add_Room_category").on("hidden.bs.modal", function() {
        $("#roomForm")[0].reset();
        $("#hid").val("");
        $("#roomForm").validate().resetForm();
        $("#roomForm").find('.error').removeClass('error');
    });

    if ($.fn.DataTable.isDataTable('#roomTable')) {
        $('#roomTable').DataTable().destroy();
    }
    $(document).on('change', '.toggle-status', function() {
        var id = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: "/admin/room/toggle-status",
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

    $('#roomTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/roomlist",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        columns: [{
                data: "category",
            },
            {
                data: "total_rooms",
            },
            {
                data: "beds_per_room",
            },
            {
                data: "charges_per_bed",
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

$(document).on('click', '#Add_Room', function() {
    $('#Add_Rooms').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Room");
    $("#modal_title").html("Add Room");
});
var validationRules = {
    category: "required",
    total_rooms: {
        required: true,
        digits: true,
        maxlength: 4,
    },
    beds_per_room: {
        required: true,
        digits: true,
        maxlength: 4,
    },
    charges: {
        required: true,
        digits: true,
        maxlength: 6,
    },
    status: "required",

};

var validationMessages = {
    category: 'This field is required',
    total_rooms: {
        required: "Please enter the Total rooms",
        digits: "Rooms must be a valid number",
        maxlength: "Rooms must be a maximum of 4 digits",
    },
    charges: {
        required: "Please enter the Charges for per Bed",
        digits: "Charges must be a valid number",
        maxlength: "Charges must be a maximum of 6 digits",
    },
    beds_per_room: {
        required: "Please enter the Beds Per Room",
        digits: "Beds must be a valid number",
        maxlength: "Beds must be a maximum of 4 digits",
    },
    status: 'This field is required',
};

$('form[id="roomForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#roomForm")[0]);
        console.log(formData);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/room/save',
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
                        $('#Add_Rooms').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#Add_Rooms').modal('hide');
                        $('#loader-container').hide();
                    }
                }
                $("#roomForm")[0].reset();
                $("#roomForm").validate().resetForm();
                $("#roomForm").find('.error').removeClass('error');
                $('#roomTable').DataTable().ajax.reload();
            }
        });
    },
});
$(document).on('click', '#edit_room', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/room/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            console.log("response", response.status);
            if (response.status == 1) {
                if (response.role_data) {
                    var roledata = response.role_data;
                    $('#Add_Rooms').modal('show');
                    $("#modal_title").html("Edit Rooms");
                    $('#hid').val(roledata.id);
                    $('#category').val(roledata.category_id);
                    $('#total_rooms').val(roledata.total_rooms);
                    $('#beds_per_room').val(roledata.beds_per_room);
                    $('#charges').val(roledata.charges_per_bed);
                    console.log(roledata.status);
                    $("input[name='status'][value='" + roledata.status + "']").prop("checked", true);
                }
            }
        },
    });
});
$(document).on("click", "#delete_room", function() {
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
                url: "/admin/room/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log("data", data);
                    if (data.status == 1) {
                        $('#roomTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});
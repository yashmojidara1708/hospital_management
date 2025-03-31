$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#roomsTable')) {
        $('#roomsTable').DataTable().destroy();
    }

    $("#add_rooms_details").on("hidden.bs.modal", function() {
        $("#RoomsForm")[0].reset();
        $("#hidden_room_id").val("");
        $("#RoomsForm").validate().resetForm();
        $("#RoomsForm").find('.error').removeClass('error');
    });

    $('#roomsTable').dataTable({
        processing: true,
        serverSide: true,
        searching: true,
        paging: true,
        pageLength: 10,
        destroy: true,

        "ajax": {
            url: "/admin/rooms/list",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $("[name='_token']").val(),
            },
        },
        columns: [
            { data: "room_number" },
            { data: "category_name" },
            { data: "beds" },
            { data: "charges" },
            { data: "status" },
            { data: "action", orderable: false },
        ],
    });

    $('#add_rooms').click(function () {
        $('#modal_title').text('Add Room');
        $('#RoomsForm')[0].reset();
        $('#hidden_room_id').val('');
        $('#add_rooms_details').modal('show');
    });

    var validationRules = {
        room_number: "required",
        category_id: "required",
        beds: { required: true, digits: true },
        charges: { required: true, number: true },
    };
    var validationMessages = {
        room_number: "Please enter the room number",
        category_id: "Please select a category",
        beds: "Please enter a valid number of beds",
        charges: "Please enter valid charges",
    };

    $('form[id="RoomsForm"]').validate({
        rules: validationRules,
        messages: validationMessages,
        submitHandler: function() {
            var formData = new FormData($("#RoomsForm")[0]);
            $('#loader-container').show();
            $.ajax({
                url: BASE_URL + '/admin/rooms/save',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if(response?.status == 1) {
                        $('#RoomsForm')[0].reset();
                        $('#hidden_room_id').val('');
                        $('#add_rooms_details').modal('hide');
                        $('#roomsTable').DataTable().ajax.reload();
                        toastr.success(response?.message);
                    } else {
                        toastr.error(response?.message);
                    }
                }
            });
        },
    });

    $(document).on('click', '#edit_rooms', function() {
        var id = $(this).data("id");
        $.ajax({
            type: "GET",
            url: "/admin/rooms/edit",
            data: { _token: $("[name='_token']").val(), id: id },
            success: function(response) {
                if (response.status == 1) {
                    var room = response?.room_data;
                    $('#add_rooms_details').modal('show');
                    $("#modal_title").html("Edit Room");
                    $('#hidden_room_id').val(room?.id);
                    $('#room_number').val(room?.room_number);
                    $('#category_id').val(room?.category_id);
                    $('#beds').val(room?.beds);
                    $('#charges').val(room?.charges);
                    $('#status').val(room?.status);
                }
            },
        });
    });

    $(document).on("click", "#delete_rooms", function() {
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
                    url: "/admin/rooms/delete",
                    data: { _token: $("[name='_token']").val(), id: id },
                    success: function(response) {
                        if (response?.status == 1) {
                            $('#roomsTable').DataTable().ajax.reload();
                            toastr.success(response?.message);
                        } else {
                            toastr.error(response?.message);
                        }
                    }
                });
            }
        });
    });

});

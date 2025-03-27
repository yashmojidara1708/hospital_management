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
        columns: [{
                data: "name",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });

    // Initialize modal
    $('#add_rooms').click(function () {
        $('#modal_title').text('Add Room Category');
        $('#RoomsForm')[0].reset();
        $('#room_id').val('');
        $('#add_rooms_details').modal('show');
    });

    var validationRules = {
        room_name: "required",
    };
    var validationMessages = {
        room_name: "Please enter the room name",
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
                        $('#room_id').val('');
                        $('#add_rooms_details').modal('hide');
                        // $("#RoomsForm").validate().resetForm();
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
            data: {
                _token: $("[name='_token']").val(),
                id: id,
            },
            success: function(response) {
                if (response.status == 1) {
                    if (response?.room_data) {
                        var roomsdata = response?.room_data;
                        $('#add_rooms_details').modal('show');
                        $("#modal_title").html("Edit Rooms");
                        $('#hidden_room_id').val(roomsdata?.id);
                        $('#room_name').val(roomsdata?.name);
                       
                    }
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
                    data: {
                        _token: $("[name='_token']").val(),
                        id: id,
                    },
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

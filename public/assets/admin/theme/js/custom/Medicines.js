$(document).ready(function() {
    $("#MedicinesForm")[0].reset();
    $("#hid").val("");

    $("#Add_Medicines_details").on("hidden.bs.modal", function() {
        $("#MedicinesForm")[0].reset();
        $("#hid").val("");
        $("#MedicinesForm").validate().resetForm();
        $("#status").val("").change();
        $("#MedicinesForm").find('.error').removeClass('error');
        $("#oldimgbox").hide();
    });

    if ($.fn.DataTable.isDataTable('#MedicinesTable')) {
        $('#MedicinesTable').DataTable().destroy();
    }

    $('#MedicinesTable').dataTable({
        searching: true,
        paging: true,
        pageLength: 10,

        "ajax": {
            url: "/admin/medicineslist",
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
                data: "stock",
            },
            {
                data: "price",
            },
            {
                data: "expiry_date",
            },
            {
                data: "action",
                orderable: false
            },
        ],
    });
    $('#loader-container').hide();
})

$(document).on('click', '#Add_Medicines', function() {
    $('#Add_Medicines_details').modal('show');
    $("#modal_title").html("");
    $("#modal_title").html("Add Medicine");
    $("#modal_title").html("Add Medicine");
});

let today = new Date();
today.setDate(today.getDate() + 1); // Set to tomorrow (future date)

let formattedDate = today.toISOString().split('T')[0]; // Format as YYYY-MM-DD
$("#expiry_date").attr("min", formattedDate);

var validationRules = {
    name: "required",
    expiry_date: "required",
    price: {
        required: true,
        number: true, // Ensures only numeric values
        min: 10, // Minimum 2-digit number (10)
        max: 99999 // Maximum 5-digit number (99999)
    },
    stock: {
        required: true,
        digits: true,
    },
};
var validationMessages = {
    name: "Please enter the medicines name",
    expiry_date: "Please select the expiry date",
    price: {
        required: "Please enter the price",
        number: "Price must be a valid number",
        min: "Price must be at least 10",
        max: "Price cannot exceed 99999"
    },
    stock: {
        required: "Please enter the stock",
        digits: "Stock must contain only numbers",
    },
};

$('form[id="MedicinesForm"]').validate({
    rules: validationRules,
    messages: validationMessages,
    submitHandler: function() {
        var formData = new FormData($("#MedicinesForm")[0]);
        $('#loader-container').show();
        $.ajax({
            url: BASE_URL + '/admin/medicines/save',
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
                        $('#Add_Medicines_details').modal('hide');
                    } else {
                        toastr.error(data.message);
                        $('#Add_Medicines_details').modal('hide');
                        $('#loader-container').hide();
                    }
                }
                $("#MedicinesForm")[0].reset();
                $("#MedicinesForm").validate().resetForm();
                $("#MedicinesForm").find('.error').removeClass('error');
                $('#MedicinesTable').DataTable().ajax.reload();
                // $('#per_details_tab').load(window.location.href + ' #per_details_tab');
            }
        });
    },
});

$(document).on("click", "#delete_medicines", function() {
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
                url: "/admin/medicines/delete",
                data: {
                    _token: $("[name='_token']").val(),
                    id: id,
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == 1) {
                        $('#MedicinesTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
});

$(document).on('click', '#edit_medicines', function() {
    var id = $(this).data("id");
    $.ajax({
        type: "GET",
        url: "/admin/medicines/edit",
        data: {
            _token: $("[name='_token']").val(),
            id: id,
        },
        success: function(response) {
            if (response.status == 1) {
                if (response.medicines_data) {
                    var medicinesdata = response.medicines_data;
                    $('#Add_Medicines_details').modal('show');
                    $("#modal_title").html("Edit Medicine");
                    $('#hid').val(medicinesdata.id);
                    $('#name').val(medicinesdata.name);
                    $('#price').val(medicinesdata.price);
                    $('#stock').val(medicinesdata.stock);
                    $('#expiry_date').val(medicinesdata.expiry_date);
                }
            }
        },
    });
});
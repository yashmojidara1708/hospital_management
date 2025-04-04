$(document).ready(function() {
    $("#save-bill-btn").on("click", function(e) {
        e.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to save this bill?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Save it!",
        }).then((result) => {
            if (result.isConfirmed) {
                saveBill(); // Call function to save the bill
            }
        });
    });

    function saveBill() {
        var formData = new FormData($("#bill-form")[0]);
        $.ajax({
            url: '/admin/bill/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if (data && Object.keys(data).length > 0) {
                    if (data.status == 1) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            }
        });
    }
});
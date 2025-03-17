$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    initializeSelect2();
    // Form validation rules
    $("#prescription-form").validate({
        submitHandler: function (form) {
            let formData = {
                patient_id: $("input[name='patient_id']").val(),
                instructions: $("#instructions").val(),
                medicines: []
            };

            $("#prescription-items tr").each(function () {
                let medicine_name = $(this).find(".medicine-select").val();
                let quantity = $(this).find("input[name='quantity[]']").val();
                let days = $(this).find("input[name='days[]']").val();

                let time = [];
                $(this).find("input[type='checkbox']:checked").each(function () {
                    time.push($(this).attr("name").replace("time[", "").replace("][]", ""));
                });

                if (medicine_name && quantity && days) {
                    formData.medicines.push({
                        medicine_name: medicine_name,
                        quantity: quantity,
                        days: days,
                        time: time
                    });
                }
            });

            // Check if at least one medicine is added
            if (formData.medicines.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Please add at least one medicine.",
                    confirmButtonColor: "#d33"
                });
                return false;
            }

            // AJAX request
            $.ajax({
                url: "/doctor/save-prescription",
                type: "POST",
                data: JSON.stringify(formData),
                processData: false,
                contentType: "application/json",
                cache: false,
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Prescription saved successfully!",
                        confirmButtonColor: "#3085d6"
                    }).then(() => {
                        $("#prescription-form")[0].reset();
                        $("#prescription-items").html("");
                        const activeTab = localStorage.getItem('activeTab') || '#pat_appointments';

                        // Redirect to the patient profile page with the active tab
                        window.location.href = `/doctor/patientprofile/${response?.patient_id}${activeTab}`;
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Failed to save prescription. Please try again.",
                        confirmButtonColor: "#d33"
                    });
                }
            });
        }
    });

    // Add new medicine row
    $(document).on("click", ".add-more-item", function () {
        let newRow = `
                        <tr>
                            <td>
                                <select class="form-control medicine-select" name="medicine_name[]" required></select>
                            </td>
                            <td><input class="form-control" type="number" name="quantity[]" required></td>
                            <td><input class="form-control" type="number" name="days[]" required></td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="time[morning][]"> Morning
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="time[afternoon][]"> Afternoon
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="time[evening][]"> Evening
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="time[night][]"> Night
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn bg-danger-light remove-medicine">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>`;

        // $("#prescription-items").append(newRow);
        let $newRow = $(newRow).appendTo("#prescription-items");
        $newRow.find(".medicine-select").select2({
            placeholder: "Select Medicine or Type New",
            multiple: true,
            tags: true,
            ajax: {
                url: '/doctor/getmedicine',
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return { id: item.name, text: item.name };
                        }),
                    };
                },
                cache: true,
            },
        });
    });

    // Remove medicine row
    $(document).on("click", ".remove-medicine", function () {
        $(this).closest("tr").remove();
    });
});

function initializeSelect2() {
    $('.medicine-select').select2({
        placeholder: "Select Medicine or Type New",
        multiple: true,
        tags: true,
        ajax: {
            url: '/doctor/getmedicine', // Laravel Route to fetch medicines
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return { id: item.id, text: item.name };
                    })
                };
            },
            cache: true
        }
    });
}
// Initialize Select2 on page load
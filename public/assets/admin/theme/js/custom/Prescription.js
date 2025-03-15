$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    initializeSelect2();
    // Form validation rules
    $("#prescription-form").validate({
        submitHandler: function(form) {
            let formData = {
                patient_id: $("input[name='patient_id']").val(),
                instructions: $("#instructions").val(),
                medicines: []
            };

            // Collect prescription items, including dynamically added rows
            $("#prescription-items tr").each(function() {
                let medicine_name = $(this).find(".medicine-select").val();
                // let medicineData = $(this).find(".medicine-select").select2('data')[0];
                // console.log("medicineData", medicineData);
                let quantity = $(this).find("input[name='quantity[]']").val();
                let days = $(this).find("input[name='days[]']").val();

                let time = [];
                $(this).find("input[type='checkbox']:checked").each(function() {
                    time.push($(this).attr("name").replace("time[", "").replace("][]", ""));
                });

                // Only add valid data
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
                alert("Please add at least one medicine.");
                return false;
            }

            console.log("formData", formData); // Debugging
            // AJAX request
            $.ajax({
                url: "/doctor/save-prescription",
                type: "POST",
                data: JSON.stringify(formData),
                processData: false,
                contentType: "application/json",
                cache: false,
                success: function(response) {
                    alert("Prescription saved successfully!");
                    $("#prescription-form")[0].reset();
                    $("#prescription-items").html(""); // Clear the table
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Failed to save prescription. Please try again.");
                }
            });
        }
    });

    // Add new medicine row
    $(document).on("click", ".add-more-item", function() {
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
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return { id: item.name, text: item.name };
                        }),
                    };
                },
                cache: true,
            },
        });
    });

    // Remove medicine row
    $(document).on("click", ".remove-medicine", function() {
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
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return { id: item.id, text: item.name };
                    })
                };
            },
            cache: true
        }
    });
}
// Initialize Select2 on page load
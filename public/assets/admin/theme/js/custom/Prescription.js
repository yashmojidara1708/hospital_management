$(document).ready(function() {
    console.log('script is running');
    initializeSelect2();
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
                <button type="button" class="btn bg-danger-light remove-medicine"><i class="far fa-trash-alt"></i></button>
            </td>
        </tr>`;

        $("#prescription-items").append(newRow);
        $(".medicine-select").select2({ // Reinitialize Select2 for newly added row
            placeholder: "Select Medicine or Type New",
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

    // Remove row when clicking the delete button
    $(document).on("click", ".remove-medicine", function() {
        $(this).closest("tr").remove();
    });


    $("#prescription-form").submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = {
            instructions: $("#instructions").val(),
            medicines: []
        };

        $("#prescription-items tr").each(function() {
            let medicine_name = $(this).find(".medicine-select").val();
            let quantity = $(this).find("input[name='quantity[]']").val();
            let days = $(this).find("input[name='days[]']").val();

            let time = [];
            $(this).find("input[type='checkbox']:checked").each(function() {
                time.push($(this).attr("name").replace("time[", "").replace("][]", ""));
            });

            if (medicine_id) {
                formData.medicines.push({
                    medicine_name: medicine_name,
                    quantity: quantity,
                    days: days,
                    time: time
                });
            }
        });

        $.ajax({
            url: "/doctor/save-prescription",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                alert("Prescription saved successfully!");
                $("#prescription-form")[0].reset();
                $("#prescription-items").html(""); // Clear the table
            },
            error: function(error) {
                console.log(error);
                alert("Failed to save prescription.");
            }
        });
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
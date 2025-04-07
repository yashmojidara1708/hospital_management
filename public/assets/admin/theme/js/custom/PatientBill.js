$(document).ready(function() {
    function numberToWords(amount) {
        const words = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen'
        ];

        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function getWords(n) {
            let str = '';

            if (n > 19) {
                str += tens[Math.floor(n / 10)] + (n % 10 ? ' ' + words[n % 10] : '');
            } else {
                str += words[n];
            }

            return str;
        }

        function convertNumberToWords(num) {
            if (num === 0) return 'Zero Rupees Only';
            if (num.toString().length > 9) return 'Overflow';

            let result = '';
            const crore = Math.floor(num / 10000000);
            num = num % 10000000;
            const lakh = Math.floor(num / 100000);
            num = num % 100000;
            const thousand = Math.floor(num / 1000);
            num = num % 1000;
            const hundred = Math.floor(num / 100);
            const rest = num % 100;

            if (crore) result += getWords(crore) + ' Crore ';
            if (lakh) result += getWords(lakh) + ' Lakh ';
            if (thousand) result += getWords(thousand) + ' Thousand ';
            if (hundred) result += getWords(hundred) + ' Hundred ';
            if (rest) {
                if (result !== '') result += ' ';
                result += getWords(rest) + ' ';
            }

            return result.trim() + ' Rupees Only';
        }

        return convertNumberToWords(Math.floor(amount));
    }

    var doctor_fee = parseFloat($('#doctor_fee').val()) || 0;
    var room_charge = parseFloat($('#room_charge').val()) || 0;
    // Calculate Subtotal
    var subtotal = doctor_fee + room_charge;
    $('#sub_total').text('₹' + subtotal.toFixed(2));

    function calculateTotal() {
        // Fetch and parse subtotal safely
        var doctor_fee = parseFloat($('#doctor_fee').val()) || 0;
        var room_charge = parseFloat($('#room_charge').val()) || 0;
        var discount_percentage = parseFloat($('#discount_percentage').val()) || 0;
        var total_days = parseInt($("input[name='total_days']").val()) || 0;
        console.log(total_days);
        // Calculate Subtotal
        var subtotal = doctor_fee + room_charge;
        $('#sub_total').text('₹' + subtotal.toFixed(2));
        // Initialize discount amount
        var discount_amount = 0;
        var grand_total = subtotal;

        // Apply discount only if admitted days >= 1

        discount_amount = (subtotal * discount_percentage) / 100;
        grand_total = subtotal - discount_amount;
        $('#discount_amount').text('- ₹' + discount_amount.toFixed(2));
        $('#grand_total').text('₹' + grand_total.toFixed(2));
        $('#grand_total_display').text('₹' + grand_total.toFixed(2));
        $('#grand_total_in_words').text(numberToWords(grand_total));

        const hiddenDiscount = document.getElementById("discount");
        const hiddenDiscountAmount = document.getElementById("discount_amount_hidden");
        const hiddenTotalAmount = document.getElementById("total_amount");

        hiddenDiscount.value = discount_percentage;
        hiddenDiscountAmount.value = discount_amount.toFixed(2);
        hiddenTotalAmount.value = grand_total.toFixed(2);

        // Show Grand Total

    }


    // Trigger calculation when discount input changes
    $('#discount_percentage').on('input', function() {
        calculateTotal();
    });

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
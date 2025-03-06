
$(document).ready(function () {
    // Load upcoming appointments when the page loads
    loadUpcomingAppointments();

    // Load today appointments when the tab is clicked
    $('#today-tab').on('click', function () {
        loadTodayAppointments();
    });

    function loadUpcomingAppointments() {
        $.ajax({
            url: "/doctor/upcoming-appointments",
            type: "GET",
            success: function (response) {
                $('#upcoming-content').html(response);
            }
        });
    }

    function loadTodayAppointments() {
        $.ajax({
            url: "/doctor/upcoming-appointments",
            type: "GET",
            success: function (response) {
                $('#today-content').html(response);
            }
        });
    }
});

$(document).ready(function() {
    console.log('script is running');
    $('#Add_Doctors').click(function() {
        $('#Add_Doctors_details').modal('show');
        $("#modal_title").html("Add Presciption");
    });


});
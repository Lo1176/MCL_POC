jQuery(document).ready(function ($) {

    // Do stuff here
    $('#customize3d').on('click', function (){
        var entretoise = $('#entrtoise').val();
        var obj = {
            entretoise: entretoise
        };
        var url = $(this).attr('data-url');
        $.post(url, obj, function (data) {
            alert(data);
        });
    });
}); // jQuery End
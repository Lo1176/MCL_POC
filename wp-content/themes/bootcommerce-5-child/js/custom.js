jQuery(document).ready(function ($) {

    // Do stuff here
    // fonction pour récupérer les attributs des produits
    $('#customize3d').on('click', function (){
        // ajouter des if
        var entretoise = $('#entretoise').val();
        var obj = {
            entretoise: entretoise
        };
        var url = $(this).attr('data-url');
        $.post(url, obj, function (data) {
            // alert(data);
        });
    });
}); // jQuery End
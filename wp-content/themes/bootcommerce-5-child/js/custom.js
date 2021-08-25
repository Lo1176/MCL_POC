jQuery(document).ready(function ($) {

    // Do stuff here
    // fonction pour récupérer les attributs des produits
    $('#customize3d').on('click', function (){
        // ajouter des if
        var entretoise = $('#entretoise').val();
        var couleur = $('#couleur').val();
        var obj = {
            entretoise: entretoise,
            couleur: couleur
        };
        var url = $(this).attr('data-url');
        $.post(url, obj, function (data) {
            var url3d = data['url3d'];
            window.location = url3d;
        });
    });
}); // jQuery End
jQuery(document).ready(function ($) {
  // Do stuff here
  // fonction pour récupérer les attributs des produits
  $("#customize3d").on("click", function () {
    // ajouter des if
    var entretoise = $("#entretoise").val();
    var couleur = $("#couleur").val();
    var obj = {
      entretoise: entretoise,
      couleur: couleur,
    };
    var url = $(this).attr("data-url");
    $.post(url, obj, function (data) {
      var url3d = data["url3d"];
      console.log(data);
      // window.location = url3d;
    });
  });

  // fonction pour récupérer les paramètres de l'URL
  function getParameter(p) {
    var url = window.location.search.substring(1); // récupère les paramètres
    var varUrl = url.split("&"); // sépare les params autour de "&"
    for (var i = 0; i < varUrl.length; i++) {
      var parameter = varUrl[i].split("=");
      if (parameter[0] == p) {
        return parameter[1];
      }
    }
  }

  var entretoiseValue = getParameter("entretoise");
  if (entretoiseValue !== undefined) {
    var element = $("#entretoise");
    element.val(entretoiseValue);
  }
  var couleurValue = getParameter("couleur");
  if (couleurValue !== undefined) {
    var element = $("#couleur");
    element.val(couleurValue);
  }
}); // jQuery End
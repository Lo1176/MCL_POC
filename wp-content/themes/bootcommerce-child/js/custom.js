jQuery(document).ready(function ($) {
  // function to change the last word
  $(document).ready(function() {
      $("div h1").each(function (index, element) {
        var heading = $(element);
        var Word_array, last_Word, first_part;

        Word_array = heading.html().split(/\s+/); // split on spaces
        last_Word = Word_array.pop(); // pop the last Word
        first_part = Word_array.join(" "); // rejoin the first words together

        heading.html(
          [first_part, ' <span class="last-word">', last_Word, "</span>"].join(
            ""
          )
        );
      });
  });
  
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

  $('.single_add_to_cart_button, button.product_type_simple').prop('disabled', false);

  // fonction pour ajouter des flèches autour de Quantity
  $("#btn-plus, #btn-minus").on("click", function (e) {
    const isNegative = $(e.target).closest("#btn-minus").is("#btn-minus");
    const input = $(e.target).closest(".inline-group").find("input");
    if (input.is("input")) {
      input[0][isNegative ? "stepDown" : "stepUp"]();
    }
  });

}); // jQuery End

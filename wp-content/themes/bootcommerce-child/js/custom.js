jQuery(document).ready(function ($) {

  // function to fix the header on scroll
  // add padding top to show content behind navbar

    $('body').css('padding-top', '34px')

    // detect scroll top or down
    if ($('.smart-scroll').length > 0) { // check if element exists
        var last_scroll_top = 0;
        $(window).on('scroll', function() {
          var scroll_top = $(this).scrollTop();
          if(scroll_top < last_scroll_top) {
              $('.smart-scroll').removeClass('scrolled-down').addClass('scrolled-up');
          }
          else {
              $('.smart-scroll').removeClass('scrolled-up').addClass('scrolled-down');
          }
          last_scroll_top = scroll_top;
      });
    }

  
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

  /** 
   * custom plus minus Qty buttons 
   */
  // WC Quantity Input
  if (!String.prototype.getDecimals) {
    String.prototype.getDecimals = function () {
      var num = this,
        match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
      if (!match) {
        return 0;
      }
      return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
    }
  }
  // Quantity "plus" and "minus" buttons
  $(document.body).on('click', '.plus, .minus', function () {
    var $qty = $(this).closest('.quantity').find('.qty'),
      currentVal = parseFloat($qty.val()),
      max = parseFloat($qty.attr('max')),
      min = parseFloat($qty.attr('min')),
      step = $qty.attr('step');

    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

    // Change the value
    if ($(this).is('.plus')) {
      if (max && (currentVal >= max)) {
        $qty.val(max);
      } else {
        $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
      }
    } else {
      if (min && (currentVal <= min)) {
        $qty.val(min);
      } else if (currentVal > 0) {
        $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
      }
    }

    // Trigger change event
    $qty.trigger('change');
  });
  // WC Quantity Input End


  // bootstrap-input-spinner
  // $("input[type='number']").inputSpinner();

  // fonction pour ajouter des flèches autour de quantity
  // $("#btn-plus, #btn-minus").on("click", function (e) {
  //   const isNegative = $(e.target).closest("#btn-minus").is("#btn-minus");
  //   const input = $(e.target).closest(".inline-group").find("input");
  //   if (input.is("input")) {
  //     input[0][isNegative ? "stepDown" : "stepUp"]();
  //   }
  // });

  // Burger Btn Animation
  const icons = document.querySelectorAll('.stripes');
  icons.forEach (icon => {  
    icon.addEventListener('click', (event) => {
      icon.classList.toggle("open");
    });
  });




}); // jQuery End

jQuery(document).ready(function ($) {
  // Do stuff here
  $(document).ready(function() {
      $("div p").each(function (index, element) {
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

}); // jQuery End

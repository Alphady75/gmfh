$(document).ready(function () {
   $(".btnFetch").click(function () {
      // disable button
      $(this).prop("disabled", true);
      // add spinner to button class="spinner-border" role="status"
      $(this).html(
         `<div class="spinner-border text-light spinner-border-sm" role="status" aria-hidden="true"><span class="visually-hidden">Traitement en cours...</span></div>`
      );
   });
});
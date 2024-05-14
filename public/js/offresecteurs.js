$(document).on('change', '#offre_secteuractivite', function () {

   let $field = $(this)
   let $form = $field.closest('form')
   let data = {}

   data[$field.attr('name')] = $field.val()

   $.post($form.attr('action'), data).then(function (data) {

      console.log(data)

      let $input = $(data).find('#offre_soussecteuractivite')

      $('#offre_soussecteuractivite').replaceWith($input)

   }).catch(function (error) {
      console.log(error)
   })
})
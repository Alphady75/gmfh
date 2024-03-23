$(document).on('change', '#post_categorie', function () {

   let $field = $(this)
   let $form = $field.closest('form')
   let data = {}

   data[$field.attr('name')] = $field.val()

   $.post($form.attr('action'), data).then(function (data) {

      console.log(data)

      let $input = $(data).find('#post_souscategorie')

      $('#post_souscategorie').replaceWith($input)

   }).catch(function (error) {
      console.log(error)
   })
})
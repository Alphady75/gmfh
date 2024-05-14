// Alternatively, you can target your input element by ID
// Replace querySelector('input[name="search_input"]') with getElementById('search_input') 
// and insert id="search_input" in your form's input field
let searchInput = document.querySelector('.location');
document.addEventListener('DOMContentLoaded', function () {
   let autocomplete = new google.maps.places.Autocomplete(searchInput, {
      types: ['geocode'],
      //componentRestrictions: { country: 'us' }
   });
   autocomplete.addListener('place_changed', function () {
      let near_place = autocomplete.getPlace();
   });
});
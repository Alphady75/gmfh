function onClickBtnFavoris(event) {
   event.preventDefault();

   const url = this.href;
   const icone = this.querySelector('i')

   axios.get(url)
      .then(function (response) {
         // handle success
         //const favoris = response.data.favoris;
         if (icone.classList.contains('far')) {
            icone.classList.replace('far', 'fa');
            //button.textContent = "J'aime"
         }
         else {
            icone.classList.replace('fa', 'far');
            //button.textContent = "J'aime plus"
         }

         console.log(response);
      }).catch(function (error) {
         if (error.status === 403) {
            window.alert("Vous ne pouvez pas ajouter une annonce en favoris sans pour autant vous connecter")
         }
         console.log(error);
      }).finally(function () {
         // always executed
      });
}

document.querySelectorAll('a.js-favoris').forEach(function (link) {

   link.addEventListener('click', onClickBtnFavoris)

})
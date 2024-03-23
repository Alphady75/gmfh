const img = document.querySelector('.photo');
const file = document.querySelector('#post_imageFile_file');

file.addEventListener('change', function () {
   const choosefile = this.files[0];
   if (choosefile) {
      const reader = new FileReader();
      reader.addEventListener('load', function () {
         img.setAttribute('src', reader.result);
      })
      reader.readAsDataURL(choosefile);
   }
})
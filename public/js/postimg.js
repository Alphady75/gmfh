const imgun = document.querySelector('.img-un');
const imgunFile = document.querySelector('#post_imageFile_file');

imgunFile.addEventListener('change', function () {
   const logochoosefile = this.files[0];
   if (logochoosefile) {
      const reader = new FileReader();
      reader.addEventListener('load', function () {
         imgun.setAttribute('src', reader.result);
      })
      reader.readAsDataURL(logochoosefile);
   }
})

const imgdeux = document.querySelector('.img-deux');
const imgDeuxFile = document.querySelector('#post_imageDeuxFile_file');

imgDeuxFile.addEventListener('change', function () {
   const photochoosefile = this.files[0];
   if (photochoosefile) {
      const reader = new FileReader();
      reader.addEventListener('load', function () {
         imgdeux.setAttribute('src', reader.result);
      })
      reader.readAsDataURL(photochoosefile);
   }
})
const imgtrois = document.querySelector('.img-trois');
const imgTroisFile = document.querySelector('#post_imageTroisFile_file');

imgTroisFile.addEventListener('change', function () {
   const photochoosefile = this.files[0];
   if (photochoosefile) {
      const reader = new FileReader();
      reader.addEventListener('load', function () {
         imgtrois.setAttribute('src', reader.result);
      })
      reader.readAsDataURL(photochoosefile);
   }
})
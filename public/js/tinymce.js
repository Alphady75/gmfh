ClassicEditor
    .create(document.querySelector('.mce'))
    .catch(error => {
        console.error(error);
    });
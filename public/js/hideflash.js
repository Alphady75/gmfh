// Fonction pour afficher l'alerte
function afficherAlerte() {
   document.getElementById('flash').style.display = 'block';
   // Appeler la fonction pour masquer l'alerte après 5 secondes
   setTimeout(function () {
     masquerAlerte();
   }, 5000); // 5000 millisecondes = 5 secondes
 }

 // Fonction pour masquer l'alerte
 function masquerAlerte() {
   document.getElementById('flash').style.display = 'none';
 }

 // Appeler la fonction pour afficher l'alerte
 afficherAlerte();
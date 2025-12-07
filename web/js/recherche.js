$(document).ready(function() {
     // Lorsque le formulaire est soumis
     $('#search-form').on('submit', function(e) {
         e.preventDefault(); // Empêcher le rechargement de la page

         $.ajax({
             url: $(this).attr('action'),  // L'URL du formulaire
             type: 'POST',
             data: $(this).serialize(),   // Sérialiser les données du formulaire
             success: function(response) {
                 // Mettre à jour la section des résultats
                 $('#resultats-container').html(response);
                 showNotification("Résultats mis à jour", "success");
             },
             error: function() {
                 showNotification("Erreur AJAX", "error");
             }
         });
     });
});
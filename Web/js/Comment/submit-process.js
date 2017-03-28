/**
 * Created by gstenek on 27/03/2017.
 */

/**
 * Ajax submiting of the comment form
 *
 */

// when doc is ready
$(document).ready(function() {
	
	// Lorsque je soumets le formulaire
	$('.js-form-comment').on('submit', function(e) {
		
		e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
		
		var $this = $(this); // L'objet jQuery du formulaire

		console.log($this.serialize());
		console.log($this.attr('action'));
		
		// Envoi de la requête HTTP en mode asynchrone
		$.ajax({
			url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
			type: 'POST', // La méthode indiquée dans le formulaire (get ou post)
			data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
			dataType: 'json',
			success: function(data) { // Je récupère la réponse du fichier PHP
				
				// supprimer les données des divs d'erreurs
				$this.find('div[class^="error-"]').empty();
				
				// s'il y a des erreurs
				if(data.hasOwnProperty('error_a')) {
					
					console.log( 'il y a des erreurs' );
					
					// générer l'html de l'erreur
					$.each( data['error_a'], function( name_field, value_error ){
						console.log(name_field+' : '+value_error);
						
						$this.find('.error-'+name_field+'').append("<p>"+value_error+"</p>");
					});
					
					// s'il n'y a pas d'erreur dans le formulaire
				}else{
					
					// on génère l'html du nouveau commentaire
					if(data['Comment']['fk_MMC'] != null)					{
						$('#comment').append("Posté par <strong>"+data['Comment']['Memberc']['login']+"</strong> le "+data['Comment']['date']);
					}else{
						$('#comment').append("Posté par <strong>"+data['Comment']['visitor']+"</strong> le "+data['Comment']['date']);
					}
					
					$('#comment').append("<p>"+data['Comment']['content']+"</p>");
					
					// on vide les champs des input
					$this.find("input[type=text], textarea").val("");
								
				}
			}
		});
	});
});

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
	$('.js-form').on('submit', function(e) {
		
		e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire
		
		var $this = $(this); // L'objet jQuery du formulaire
		
		$this.find("#btn-submit").attr("disabled", true); // disable button after submit

		console.log($this.serialize());
		console.log($this.attr('action'));
		console.log('Début process : '+$this.find("#btn-submit").attr("disabled"));
		$this.css('background-image','url(\'http://media.giphy.com/media/sIIhZliB2McAo/giphy.gif\')');
		$this.css('background-size','100% 100%');
		
		// Envoi de la requête HTTP en mode asynchrone
		$.ajax({
			url: $this.attr('data-js-action'), // Le nom du fichier indiqué dans le formulaire
			type: 'POST', // La méthode indiquée dans le formulaire (get ou post)
			data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
			dataType: 'json',
			success: function(data) { // Je récupère la réponse du fichier PHP
				$this.removeAttr('style');
				// supprimer les données des divs d'erreurs
				$this.find('div[class^="error-"]').empty();
				
				// s'il y a des erreurs
				if(data['content'].hasOwnProperty('error_a')) {
					
					console.log( 'il y a des erreurs' );
					
					// générer l'html de l'erreur
					$.each( data['content']['error_a'], function( name_field, value_error ){
						console.log(name_field+' : '+value_error);
						
						$this.find('.error-'+name_field+'').append("<p>"+value_error+"</p>");
					});
					
					// s'il n'y a pas d'erreur dans le formulaire
				}else{
					
					appendComment(data.content.Comment,data.content.url_delete, data.content.url_update);
										
					// on vide les champs des input
					$this.find("input[type=text], textarea").val("");
								
				}
			},
			complete: function(){
				$this.find("#btn-submit").attr("disabled", false); // enable button after ajax completed
				console.log('Fin process : '+$this.find("#btn-submit").attr("disabled"));
			}
		})});
});

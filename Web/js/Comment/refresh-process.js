/**
 * Created by gstenek on 27/03/2017.
 */

/**
 * Ajax refrech of the list of comment
 *
 */

function refreshComment() {
	
	var $comment_wrapper = $( '#comment' ), $comment;
	console.log( 'debut : ' + $comment_wrapper.attr( 'data-js-action' ) );
	
	$.ajax( {
		url      : $comment_wrapper.attr( 'data-js-action' ),
		/*type     : 'POST', // La méthode indiquée dans le formulaire (get ou post)
		data     : {
			lastcomment : ((($comment = $comment_wrapper.find( '.js-comment-wrapper:last' )) && $comment.length == 1) ? $comment.attr( 'data-id' ) : 0)
		},*/
		dataType : 'json',
		success  : function( data ) {
			console.log( 'success' );
			// s'il ya eu des nouveaux commmentaires
			if ( 'valid' !== data.status ) {
				return;
			}
			
			// pour chaque commentaire nouveau, l'inséré en html dans la div comment
			$.each( data.content.Comment_a, function( index, Comment ) {
				
				var url_update = null;
				var url_delete = null;
				// si le user est l'admin il peut supprimer ou modifier le commentaire que l'on doit afficher
				if ( typeof data.content.url_update_a !== 'undefined' ) {
					url_update = data.content.url_update_a[ Comment.id ];
					url_delete = data.content.url_delete_a[ Comment.id ];
				}
				
				// ajout à l'écran
				appendComment( Comment, url_delete, url_update );
				
				
			} );
		},
		complete : function() {
			// Schedule the next request when the current one's complete
			
			setTimeout( refreshComment, 25000 );
			console.log( 'fin' );
		}
	} )
};


function appendComment( Comment, url_delete, url_update ) {
	if ( $( '#comment-' + Comment.id ).length ) {
		return;
	}
	
	$( '#comment').append( generateComment( Comment, url_delete, url_update ) );
		
	var $div_comment = $('#comment');
	
	$div_comment.find('.js-comment-wrapper').sort(function(a, b) {
				return +a.dataset.id - +b.dataset.id;
			}).appendTo($div_comment);
}

function generateComment( Comment, url_delete, url_update ) {
	console.log( Comment );
	
	if ( typeof url_delete === 'undefined' ) {
		url_delete = null;
	}
	if ( typeof url_update === 'undefined' ) {
		url_update = null;
	}
	
	var $new_comment = $( '<div></div>' )
		.attr( 'id', 'comment-' + Comment.id )
		.attr( 'data-id', Comment.id )
		.addClass( 'js-comment-wrapper' )
		.append( 'Posté par ', $( '<strong></strong>' )
			.html( Comment.visitor ? Comment.visitor : Comment.Memberc.login ), ' le ', Comment.date );
	
	if ( null !== url_delete && null !== url_update ) {
		//@formatter:off
		$new_comment.append(
			' - ',
			$('<a></a>')
				.attr('href',url_delete)
				.html('Supprimer'),
			' | ',
			$('<a></a>')
				.attr('href',url_update)
				.html('Modifier')
		);
		//@formatter:on
	}
	
	$new_comment.append( $( '<p></p>' )
		.html( Comment.content ) );
	
	return $new_comment;
}


// when doc is ready
$( document ).ready( function() {
	refreshComment();
} );

<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 15:25
 */
//var_dump($Newg);
?>
	<script src="./js/jquery-3.2.0.min.js"></script>
	<script src="./js/Comment/submit-process.js"></script>
	<script src="./js/Comment/refresh-process.js"></script>
	<p>Par <em><?= $Newg[ 'Memberc' ][ 'login' ] ?></em>, le <?= $Newg[ 'Newc' ][ 'date_creation' ] ?></p>
	<h2><?= htmlspecialchars( $Newg->title() ) ?></h2>
	<p><?= htmlspecialchars( $Newg->content() ) ?></p>

<?php if ( $Newg[ 'Newc' ][ 'date_creation' ] != $Newg->date_edition() ) : ?>
	<p style="text-align: right;">
		<small><em>Modifiée le <?= $Newg->date_edition() ?></em></small>
	</p>
<?php endif; ?>
<?php if ( isset( $href_edit ) ): ?>
	<p><a href="<?= $href_edit ?>">Modifier</a></p>
<?php endif; ?>
<?php require __DIR__ . '/../../../../Templates/form.php'; ?>
<?php if ( empty( $comments ) ): ?>
	<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
<?php endif; ?>
	<div id="comment" data-js-action="<?= $href_refresh_comment_list ?>" >
		<?php foreach ( $comments as $Commentc ): ?>
			<div id="comment-<?= $Commentc['id'] ?>" data-id="<?= $Commentc['id'] ?>" class="js-comment-wrapper">
				Posté par
				<strong><?= $Commentc[ 'fk_MMC' ] === null ? htmlspecialchars( $Commentc[ 'visitor' ] ) : htmlspecialchars( $Commentc['Memberc'][ 'login' ] ) ?></strong> le <?= $Commentc[ 'date' ] ?>
				<?php if ( $user->isAuthenticated() && $user->getAttribute( 'Memberc' )->isTypeAdmin() ): ?> -
					<a href=<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getRouteFromAction( 'News', 'BuildCommentForm', array( 'id' => $Commentc[ 'id' ] ) )
													 ->generateHref() ?>>Modifier</a> |
					<a href=<?= \OCFram\RouterFactory::getRouter( 'Backend' )->getRouteFromAction( 'News', 'ClearComment', array( 'id' => $Commentc[ 'id' ] ) )
													 ->generateHref() ?>>Supprimer</a>
				
				<?php endif; ?>
				<p><?= htmlspecialchars( $Commentc[ 'content' ] ) ?></p>
			</div>
		<?php endforeach; ?>
	</div>

<?php require __DIR__ . '/../../../../Templates/form.php'; ?>
<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 15:25
 */
?>
<p>Par <em><?= $Newg->fk_MMC()->login() ?></em>, le <?= $Newg->fk_NNC()->date_creation()?></p>
<h2><?= $Newg->title() ?></h2>
<p><?= nl2br($Newg->content()) ?></p>

<?php if ($Newg->fk_NNC()->date_creation() != $Newg->date_edition()) { ?>
	<p style="text-align: right;"><small><em>Modifiée le <?= $Newg->date_edition() ?></em></small></p>
<?php } ?>

<p><a href="commenter-<?= $Newg->fk_NNC()->id() ?>.html">Ajouter un commentaire</a></p>

<?php
if (empty($comments))
{
	?>
	<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
	<?php
}

foreach ($comments as $comment)
{
	?>
	<fieldset>
		<legend>
			Posté par <strong><?= htmlspecialchars($comment['auteur']) ?></strong> le <?= $comment['date']->format('d/m/Y à H\hi') ?>
			<?php if ($user->isAuthenticated()) { ?> -
				<a href="admin/comment-update-<?= $comment['id'] ?>.html">Modifier</a> |
				<a href="admin/comment-delete-<?= $comment['id'] ?>.html">Supprimer</a>
			<?php } ?>
		</legend>
		<p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
	</fieldset>
	<?php
}
?>

<p><a href="commenter-<?= $Newg->fk_NNC()->id() ?>.html">Ajouter un commentaire</a></p>
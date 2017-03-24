<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 15:25
 */
?>
<p>Par <em><?= $Newg->fk_MMC()->login() ?></em>, le <?= $Newg->fk_NNC()->date_creation()?></p>
<h2><?= htmlspecialchars($Newg->title()) ?></h2>
<p><?= htmlspecialchars($Newg->content()) ?></p>

<?php if ($Newg->fk_NNC()->date_creation() != $Newg->date_edition()) { ?>
	<p style="text-align: right;"><small><em>Modifiée le <?= $Newg->date_edition() ?></em></small></p>
<?php } ?>
<?php if($this->app->user()->isAuthenticated() && (($this->app->user()->getAttribute('Memberc')->id() == $Newg->fk_MMC()->id())	|| $this->app->user()->getAttribute('Memberc')->isTypeAdmin())):?>
	<p><a href="/news-update-<?= $Newg['fk_NNC']['id'] ?>.html">Modifier</a></p>
<?php endif; ?>
<p><a href="commenter-<?= $Newg['fk_NNC']['id'] ?>.html">Ajouter un commentaire</a></p>

<?php
if (empty($comments))
{
	?>
	<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
	<?php
}

foreach ($comments as $Commentc)
{
	?>
	<div id="comment">
		<legend>
			Posté par <strong><?= $Commentc['fk_MMC'] === NULL ? htmlspecialchars($Commentc['visitor']) : htmlspecialchars($Commentc->References('Memberc')['login']) ?></strong> le <?= $Commentc['date'] ?>
			<?php if (	$user->isAuthenticated() && $user->getAttribute('Memberc')->isTypeAdmin()) { ?> -
				<a href="admin/comment-update-<?= $Commentc['id'] ?>.html">Modifier</a> |
				<a href="admin/comment-delete-<?= $Commentc['id'] ?>.html">Supprimer</a>
		</legend>
		<?php } ?>
		<p><?= htmlspecialchars($Commentc['content']) ?></p>
	</div>
	<?php
}
?>

<p><a href="commenter-<?= $Newg->fk_NNC()->id() ?>.html">Ajouter un commentaire</a></p>
<?=  \OCFram\RouterFactory::getRouter('Frontend')->getRouteFromAction('News','BuildNewsDetail',array('id' => '5'))->url();?>
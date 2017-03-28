<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 15:25
 */
?>
	<script src="./js/jquery-3.2.0.min.js"></script>
	<script src="./js/Comment/submit-process.js"></script>
<p>Par <em><?= $Newg->fk_MMC()->login() ?></em>, le <?= $Newg->fk_NNC()->date_creation()?></p>
<h2><?= htmlspecialchars($Newg->title()) ?></h2>
<p><?= htmlspecialchars($Newg->content()) ?></p>

<?php if ($Newg->fk_NNC()->date_creation() != $Newg->date_edition()) { ?>
	<p style="text-align: right;"><small><em>Modifiée le <?= $Newg->date_edition() ?></em></small></p>
<?php } ?>
<?php if($this->app->user()->isAuthenticated() && (($this->app->user()->getAttribute('Memberc')->id() == $Newg->fk_MMC()->id())	|| $this->app->user()->getAttribute('Memberc')->isTypeAdmin())):?>
	<p><a href=<?= \OCFram\RouterFactory::getRouter('Frontend')->getRouteFromAction('News','BuildNews',array('id' => $Newg['fk_NNC']['id']))->generateHref() ?>>Modifier</a></p>
<?php endif; ?>
<?php require __DIR__ . '/../../../../Templates/form.php';?>
<?php
if (empty($comments))
{
	?>
	<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
	<?php
}
	
	?><div id="comment">
	<?php
foreach ($comments as $Commentc):?>
	
			Posté par <strong><?= $Commentc['fk_MMC'] === NULL ? htmlspecialchars($Commentc['visitor']) : htmlspecialchars($Commentc->References('Memberc')['login']) ?></strong> le <?= $Commentc['date'] ?>
	<?php if (	$user->isAuthenticated() && $user->getAttribute('Memberc')->isTypeAdmin()): ?> -
	<a href=<?= \OCFram\RouterFactory::getRouter('Backend')->getRouteFromAction('News','BuildCommentForm',array('id' => $Commentc['id']))->generateHref() ?> >Modifier</a> |
	<a href=<?= \OCFram\RouterFactory::getRouter('Backend')->getRouteFromAction('News','ClearComment',array('id' => $Commentc['id']))->generateHref() ?>>Supprimer</a>

	<?php endif; ?>
	<p><?= htmlspecialchars($Commentc['content']) ?></p>
	
	
	<?php
endforeach;
?>
	</div>

<?php require __DIR__ . '/../../../../Templates/form.php';?>
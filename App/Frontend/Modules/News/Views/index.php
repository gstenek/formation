<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:42
 */
?>
<h1><?= $title ?></h1>
<?php
foreach ($listeNews as $Newg)
{
	?>
	<h2><a href="news-<?= $Newg->fk_NNC()->id() ?>.html"><?= $Newg->title() ?></a></h2>
	<p><?= nl2br($Newg->content()) ?></p>
	<p><i><?= $Newg->date_edition() ?> </i> par <b><?= $Newg->fk_MMC()->login() ?></b></p>
	<?php
}
?>
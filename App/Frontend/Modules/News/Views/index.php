<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:42
 */
foreach ($listeNews as $Newg)
{
	?>
	<h2><a href="news-<?= $Newg->fk_NNC()->id() ?>.html"><?= $Newg->title() ?></a></h2>
	<p><?= nl2br($Newg->content()) ?></p>
	<?php
}
?>
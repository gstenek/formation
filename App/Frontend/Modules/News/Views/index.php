<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:42
 */
foreach ($listeNews as $news)
{
	?>
	<h2><a href="news-<?= $news['id'] ?>.html"><?= $news['titre'] ?></a></h2>
	<p><?= nl2br($news['contenu']) ?></p>
	<p>TEST DATA</p>
	<?php
}
?>
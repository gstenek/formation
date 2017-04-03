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
/** @var \Entity\Newg $Newg */
foreach ($listeNews as $Newg)
{
	?>
	<h2><a href=<?= \App\Frontend\Modules\News\NewsController::getLinkToBuildNewsDetail($Newg) ?>><?= htmlspecialchars($Newg['title']) ?></a></h2>
	<p><?= nl2br(htmlspecialchars($Newg['content'])) ?></p>
	<p><i><?= $Newg['date_edition'] ?> </i> par <b><?= htmlspecialchars($Newg['Memberc']['login']) ?></b></p>
	<?php
}
?>
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:33
 */
$Router_backend = \OCFram\RouterFactory::getRouter('Backend');
$Router_frontend = \OCFram\RouterFactory::getRouter('Frontend');
?>
<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

<table>
	<tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
	<?php
	foreach ($listeNews as $Newg)
	{
		echo '<tr><td>', htmlspecialchars($Newg->fk_MMC()->login()), '</td><td>', htmlspecialchars($Newg->title()), '</td><td>le ', htmlspecialchars($Newg->fk_NNC()->date_creation()), '</td><td>', ($Newg->fk_NNC()->date_creation() == $Newg->date_edition() ? '-' : 'le '.$Newg->date_edition()), '</td><td><a href="'.$Router_frontend->getRouteFromAction('News','BuildNews',array('id' => $Newg->fk_NNC()->id()))->generateHref().'"><img src="/images/update.png" alt="Modifier" /></a> <a href="', $Router_backend->getRouteFromAction('News', 'ClearNews', array('id' => $Newg->fk_NNC()->id()))->generateHref(), '"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
	}
	?>
</table>
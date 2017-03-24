<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:33
 */
?>
<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

<table>
	<tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
	<?php
	foreach ($listeNews as $Newg)
	{
		echo '<tr><td>', $Newg->fk_MMC()->login(), '</td><td>', $Newg->title(), '</td><td>le ', $Newg->fk_NNC()->date_creation(), '</td><td>', ($Newg->fk_NNC()->date_creation() == $Newg->date_edition() ? '-' : 'le '.$Newg->date_edition()), '</td><td><a href="/news-update-', $Newg->fk_NNC()->id(), '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="news-delete-', $Newg->fk_NNC()->id(), '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
	}
	?>
</table>
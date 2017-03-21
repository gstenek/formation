<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:28
 */
?>
<h2><?= $title ?></h2>

<form method='POST' action=<?= $action ?> >
	<p>
		<?= $form ?>
		
		<input type="submit" name="submit" value=<?= $submit ?> />
	</p>
</form>

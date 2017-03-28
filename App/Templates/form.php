<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:28
 */
?>
<h2><?= $title_form ?></h2>
<?php if(isset($infos)){echo '<h3>'.htmlspecialchars($infos).'</h3>';}?>

<form <?php if(isset($method)){echo ' method="'.$method.'"';}else{echo 'method="POST"';} echo ' action="'.$action.'" ';?> class="js-form-comment" >
	<p>
		<?= $form ?>
		
		<input type="submit" name="submit" value=<?= $submit ?> />
	</p>
</form>

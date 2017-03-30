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

<form <?php if(isset($method)){echo ' method="'.$method.'"';}else{echo 'method="POST"';} echo ' action="'.$action.'" ';?> data-js-action="<?php if(isset($js_action)):echo $js_action; endif; ?>" class="js-form" >
	<p>
		<?= $form ?>
		
		<input type="submit" name="submit" value="<?= $submit ?>" id="btn-submit" />
	</p>
</form>

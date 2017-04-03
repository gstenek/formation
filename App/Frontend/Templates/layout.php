<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 15:40
 */
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="./js/jquery-3.2.0.min.js"></script>
		<title>
			<?= isset($title) ? $title : 'Mon super site' ?>
		</title>		<meta charset="utf-8" />
		
		<link rel="stylesheet" href="/css/Envision.css" type="text/css" />
	</head>
	
	<body>
		<div id="wrap">
			<header>
				<h1><a href="/">Mon super site</a></h1>
				<p>Comment ça, il n'y a presque rien ?</p>
			</header>
			
			<nav>
				<?php if(isset($menu)): echo $menu; endif;?>
			</nav>
			
			<div id="content-wrap">
				<section id="main">
					<?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
					
					<?= $content ?>
				</section>
			</div>
			
			<footer></footer>
		</div>
	</body>
</html>

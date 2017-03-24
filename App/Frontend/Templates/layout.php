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
				<ul>
					<li><a href="/">Accueil</a></li>
					<?php if ($user->isAuthenticated()) { ?>
						<?php if ($user->getAttribute('Memberc')->fk_MMY() == \Entity\Memberc::MMY_ADMIN) { ?>
							<li><a href="/admin/">Admin</a></li>
						<?php } ?>
						<li><a href="/news-insert.html">Ajouter une news</a></li>
						<li><a href="/logout">Se déconnecter</a></li>
						<li><?= $user->getAttribute('Memberc')->login()?></li>
					<?php }else{ ?>
						<li><a href="/login">Se connecter</a></li>
						<li><a href="/subscription">S'inscrire</a></li>
					<?php } ?>
				</ul>
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

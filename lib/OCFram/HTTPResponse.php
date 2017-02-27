<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:04
 */

namespace OCFram;

class HTTPResponse extends ApplicationComponent
{
	protected $page;
	
	public function addHeader($header)
	{
		header($header);
	}
	
	public function redirect($location)
	{
		header('Location: '.$location);
		exit;
	}
	
	public function redirect404()
	{
		// Creation de l'instance de la classe Page
		$this->page = new Page($this->app);
		
		// Assignation du fichier faisant office de vue pour l'erreur 404
		$this->page->setContentFile(__DIR__.'/../../Errors/404.html');
		
		// Ajout d'un header
		$this->addHeader('HTTP/1.0 404 Not Found');
		
		// Envoi de la reponse
		$this->send();
	}
	
	public function send()
	{
		// Actuellement, cette ligne a peu de sens dans votre esprit.
		// Promis, vous saurez vraiment ce qu'elle fait d'ici la fin du chapitre
		// (bien que je suis sûr que les noms choisis sont assez explicites !).
		exit($this->page->getGeneratedPage());
	}
	
	public function setPage(Page $page)
	{
		$this->page = $page;
	}
	
	// Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
	public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
	{
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

}
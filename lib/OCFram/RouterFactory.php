<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 24/03/2017
 * Time: 18:52
 */

namespace OCFram;


class RouterFactory {
	
	protected static $Router_a = [];
	
	/**
	 * @return Router
	 */
	public static function getRouter($application_name) {
		self::buildRouteur($application_name);
		return 	self::$Router_a[$application_name];
	}
	
	/**
	 * @param $application_name
	 */
	private static function buildRouteur($application_name) {
		if(isset(self::$Router_a[$application_name])) {
			return;
		}
		$router = new Router;
		
		$xml = new \DOMDocument;
		$xml->load(__DIR__.'/../../App/'.$application_name.'/Config/routes.xml');
		
		$routes = $xml->getElementsByTagName('route');
		
		// On parcourt les routes du fichier XML.
		foreach ($routes as $route)
		{
			$vars = [];
			$alias ='';
			// On regarde si des variables sont présentes dans l'URL.
			if ($route->hasAttribute('vars'))
			{
				$vars = explode(',', $route->getAttribute('vars'));
			}
			
			if ($route->hasAttribute('alias'))
			{
				$alias = $route->getAttribute('alias');
			}
			
			// On ajoute la route au routeur.
			$router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars, $alias));
		}
			
		self::$Router_a[$application_name]= $router;
	
	}

	
}
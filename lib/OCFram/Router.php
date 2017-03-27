<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:25
 *
 * Get the route corresponding to the asked URL.
 * Build a list of routs.
 */

namespace OCFram;

class Router
{
	protected $routes = [];
	
	const NO_ROUTE = 1;
	
	public function addRoute(Route $route)
	{
		if (!in_array($route, $this->routes))
		{
			$this->routes[] = $route;
		}
	}
	
	public function getRoute($url)
	{
		
		foreach ($this->routes as $route)
		{
			// Si la route correspond à l'URL
			if (($varsValues = $route->match($url)) !== false)
			{
				// Si elle a des variables
				if ($route->hasVars())
				{
					$varsNames = $route->varsNames();
					$listVars = [];
					
					// On crée un nouveau tableau clé/valeur
					// (clé = nom de la variable, valeur = sa valeur)
					foreach ($varsValues as $key => $match)
					{
						// La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
						if ($key !== 0)
						{
							$listVars[$varsNames[$key - 1]] = $match;
						}
					}
					
					// On assigne ce tableau de variables à la route
					$route->setVars($listVars);
					
				}
				return $route;
			}
		}
		
		throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
	}
	
	
	/**
	 * @param       $module
	 * @param       $action
	 * @param array $vars
	 *
	 * @return Route
	 * @throws \RuntimeException
	 */
	public function getRouteFromAction($module, $action ,array $vars = [])
	{
		foreach ($this->routes as $route) // pour chaque routes
		{
			// Si les actions et modules correspondent à la route
			if($route->matchModuleAction($module,$action,$vars))
			{
				if($route->hasVars())
				{
					if(count($route->varsNames()) == count($vars))
					{
						$route->setVars($vars);
					}else{
						throw new \RuntimeException('La route ne correspond (nombre de vars invalide)', self::NO_ROUTE);
					}
				}
				
				return $route;
			}
		}
		throw new \RuntimeException('Aucune route ne correspond à ('.$module.$action.')', self::NO_ROUTE);
	}
}
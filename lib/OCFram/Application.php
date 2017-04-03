<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 15:52
 *
 * Handle the execution of the script
 */

namespace OCFram;

abstract class Application {
	protected $httpRequest;
	protected $httpResponse;
	protected $name = '';
	protected $user;
	protected $config;
	
	public function __construct()
	{
		
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->name = '';
		$this->user = new User;
		$this->config = new Config($this);
	}
	
	/**
	 * @return BackController
	 */
	public function getController()
	{
		$router = RouterFactory::getRouter($this->name());
		
		try
		{
			// On récupère la route correspondante à l'URL.
			$matchedRoute = $router->getRoute($this->httpRequest->requestURI());
		}
		catch (\RuntimeException $e)
		{
			if ($e->getCode() == Router::NO_ROUTE)
			{
				// Si aucune route ne correspond, c'est que la page demandée n'existe pas.
				$this->httpResponse->redirect404();
			}
		}
		
		// On ajoute les variables de l'URL au tableau $_GET.
		$_GET = array_merge($_GET, $matchedRoute->vars());
		
		$controllerClass = 'App\\'.$this->name.'\\Modules\\'.$matchedRoute->module().'\\'.$matchedRoute->module().'Controller';
		
		// On instancie le contrôleur.
		/** @var BackController $Controller */
		$Controller =  new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
	
		$Controller->authorizer()->checkFilter();
		
		return $Controller;
	}
	
	abstract public function run();
	
	/**
	 * @return HTTPRequest
	 */
	public function httpRequest()
	{
		return $this->httpRequest;
	}
	
	/**
	 * @return HTTPResponse
	 */
	public function httpResponse()
	{
		return $this->httpResponse;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function user()
	{
		return $this->user;
	}
	
	public function config()
	{
		return $this->config;
	}
}
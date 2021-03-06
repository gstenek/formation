<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 15:55
 *
 * Base class that each controller will inherit
 *
 * Executes an action from the Module with the corresponding page Object
 * to generate the view to the visitor.
 */

namespace OCFram;

abstract class BackController extends ApplicationComponent {
	
	protected $action = '';
	protected $module = '';
	protected $page = null;
	protected $view = '';
	protected $managers = null;
	protected $authorizer = null;
	
	public function __construct(Application $app, $module, $action)
	{
		parent::__construct($app);
		
		$this->managers = new Managers('PDO', PDOFactory::getMysqlConnexion() );
		$this->page = new Page($app);
		
		$this->setModule($module);
		$this->setAction($action);
		$this->setView($action);
		$this->setAuthorizer(new Authorizer($app));
		
		if ($this instanceof Filterable) {
			$filter = $this->getFilterableFilter();
			if (null === $filter) {
				throw new \Exception('Filter are not defined for action '.$action);
			}
			$this->authorizer()->addFilter( $this->getFilterableFilter() );
		}
		
	}
	
	public function execute()
	{
		$method = 'execute'.ucfirst($this->action);
		
		if (!is_callable([$this, $method]))
		{
			throw new \RuntimeException('L\'action "'.$this->action.'" n\'est pas définie sur ce module');
		}
		
		$this->$method($this->app->httpRequest());
	}
	
	public function page()
	{
		return $this->page;
	}
	
	public function setModule($module)
	{
		if (!is_string($module) || empty($module))
		{
			throw new \InvalidArgumentException('Le module doit être une chaine de caractères valide');
		}
		
		$this->module = $module;
	}
	
	public function setAction($action)
	{
		if (!is_string($action) || empty($action))
		{
			throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
		}
		$this->action = $action;
	}
	
	public function setView($view)
	{
		if (!is_string($view) || empty($view))
		{
			throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
		}
		
		$this->view = $view;
		
		$this->page->setContentFile(__DIR__.'/../../App/'.$this->app->name().'/Modules/'.$this->module.'/Views/'.$this->view.'.php');
	}
	
	/**
	 * @return null|Managers
	 */
	public function managers() {
		return $this->managers;
	}
	
	
	/**
	 * @return Authorizer
	 */
	public function authorizer() {
		return $this->authorizer;
	}
	
	/**
	 * @param Authorizer $authorizer
	 */
	public function setAuthorizer(Authorizer $authorizer ) {
		$this->authorizer = $authorizer;
	}
}
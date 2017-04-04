<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:40
 *
 * Handles the managers. Specify the API and DAO used
 */

namespace OCFram;


class Managers
{
	protected $api = null;
	protected $dao = null;
	
	/** @var Manager[]  */
	protected $managers = [];
	protected $managers_cached = [];
	
	public function __construct($api, $dao)
	{
		$this->api = $api;
		$this->dao = $dao;
	}
	
	public function getManagerOf($module, $use_cache = true)
	{
		if (!is_string($module) || empty($module))
		{
			throw new \InvalidArgumentException('Le module spécifié est invalide');
		}
		
		if (!isset($this->managers[$module]))
		{
			$manager = '\\Model\\'.$module.'Manager'.$this->api;
			
			$this->managers[$module] = new $manager($this->dao, $this);
		}
		
		if (!$use_cache) {
			return $this->managers[ $module ];
		}
		
		
		if (!isset($this->managers_cached[$module])) {
			$this->managers_cached[$module] = new ManagerCached($this->managers[$module]);
		}
		return $this->managers_cached[$module];
	}
	
}
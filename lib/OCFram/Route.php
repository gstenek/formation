<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:26
 *
 * Build an object route with its components
 */

namespace OCFram;

class Route
{
	protected $action;
	protected $module;
	protected $url;
	protected $varsNames;
	protected $alias;
	protected $vars = [];
	
	public function __construct($url, $module, $action, array $varsNames)
	{
		$this->setUrl($url);
		$this->setModule($module);
		$this->setAction($action);
		$this->setVarsNames($varsNames);
	}
	
	public function hasVars()
	{
		return !empty($this->varsNames);
	}
	
	public function match($url)
	{
		if (preg_match('`^'.$this->url.'$`', $url, $matches))
		{
			return $matches;
		}
		else
		{
			return false;
		}
	}
	
	public function matchModuleAction($module, $action)
	{
		if ($this->module == $module && $this->action == $action)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function setAction($action)
	{
		if (is_string($action))
		{
			$this->action = $action;
		}
	}
	
	public function setModule($module)
	{
		if (is_string($module))
		{
			$this->module = $module;
		}
	}
	
	public function setUrl($url)
	{
		if (is_string($url))
		{
			$this->url = $url;
		}
	}
	
	public function setVarsNames(array $varsNames)
	{
		$this->varsNames = $varsNames;
	}
	
	public function setVars(array $vars)
	{
		$this->vars = $vars;
	}
	
	public function action()
	{
		return $this->action;
	}
	
	public function module()
	{
		return $this->module;
	}
	public function url()
	{
		return $this->url;
	}
	
	public function vars()
	{
		return $this->vars;
	}
	
	public function varsNames()
	{
		return $this->varsNames;
	}
	
	public function generateUrl()
	{
		foreach ($this->vars() as $key => $value)
		{
			
		}
	}
	
	
}
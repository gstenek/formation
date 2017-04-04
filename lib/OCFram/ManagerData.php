<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:46
 * Base class of the managers
 */

namespace OCFram;


abstract class ManagerData extends Manager {
	
	protected $dao;
	protected $Managers;
	
	public function __construct($dao, Managers $Managers)
	{
		$this->dao = $dao;
		$this->Managers = $Managers;
	}
	
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:46
 * Base class of the managers
 */

namespace OCFram;


abstract class Manager {
	protected $dao;
	
	public function __construct($dao)
	{
		$this->dao = $dao;
	}
}
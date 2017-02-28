<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:41
 */

namespace OCFram;

abstract class Validator
{
	protected $errorMessage;
	
	public function __construct($errorMessage)
	{
		$this->setErrorMessage($errorMessage);
	}
	
	abstract public function isValid($value);
	
	public function setErrorMessage($errorMessage)
	{
		if (is_string($errorMessage))
		{
			$this->errorMessage = $errorMessage;
		}
	}
	
	public function errorMessage()
	{
		return $this->errorMessage;
	}
}
<?php
namespace OCFram;

class NotExistValidator extends Validator
{
	protected $manager;
	protected $functionname;
	
	public function __construct( $errorMessage, Manager $manager, $function) {
		
		parent::__construct( $errorMessage );
		
		$this->setManager($manager);
		$this->setFunctionname($function);
	}
	
	public function isValid($value)
	{
		return  !($this->manager->{$this->functionname}($value));
	}
	
	public function setManager($manager)
	{
		$this->manager = $manager;
	}
	
	public function setFunctionname($functionname)
	{
		$this->functionname= $functionname;
	}
}
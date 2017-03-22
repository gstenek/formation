<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 21/03/2017
 * Time: 15:26
 */
namespace OCFram;

class ConfirmValidator extends Validator
{
	protected $field;
	
	public function __construct($errorMessage,Field $field)
	{
		parent::__construct($errorMessage);
		
		$this->setField($field);
	}
	
	public function isValid($value)
	{
	
			if($value == $this->field->value()) return true;
			else return false;
		
	}
	
	public function setField(Field $field)
	{
		$this->field = $field;
	}
	
}
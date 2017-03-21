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
	protected $fieldname;
	
	public function __construct($errorMessage, $fieldname)
	{
		parent::__construct($errorMessage);
		
		$this->setField($fieldname);
	}
	
	public function isValid($value)
	{
		if(isset($_POST[$this->fieldname])){
			if($value == $_POST[$this->fieldname]) return true;
		}
			return false;
		
	}
	
	public function setField($fieldname)
	{
		$this->fieldname = $fieldname;
	}
	
}
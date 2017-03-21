<?php
namespace OCFram;

class EmailValidator extends Validator
{
	public function isValid($value)
	{
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			return false;
		}else{
			return true;
		}
	}
}
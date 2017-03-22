<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:43
 */

namespace OCFram;

class StringValidator extends Validator
{
	protected $characters;
	
	public function __construct($errorMessage,array $characters)
	{
		parent::__construct($errorMessage);
		
		$this->setCharacters($characters);
	}
	
	public function isValid($value)
	{
		foreach ( $this->characters as $key => $character ) {
			
			$without_character = str_replace($character,'',$value);
			if ( !( strlen($without_character) == strlen($value)) ) {
				
				return false;
			}
		}
		return true;
	}
	
	/**
	 * @param mixed $characters
	 */
	public function setCharacters(array $characters ) {
		$this->characters = $characters;
	}
}
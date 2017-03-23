<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 22/03/2017
 * Time: 15:17
 */

namespace Entity;


use OCFram\Entity;

class Newc extends Entity {
	
	protected $fk_MMC,
			$fk_NNE,
			$date_creation;
	
	const NNE_INVALID = 1;
	const NNE_VALID = 2;
	
	const CODE_TABLE = 'NNC';
	
	public function __construct( array $donnees = [] ) {
		
		$result = [];
		
		$simple_column = Newc::CODE_TABLE.'_';
		foreach ($donnees as $key => $value)
		{
			
			$newkey = str_replace($simple_column,'',$key);
			if(!(strlen($newkey) == strlen($key))){
				if(is_callable([$this,$newkey]))
				{
					$result[$newkey] = $value;
				}
			}elseif (is_callable([$this,$key])){
				$result[$key] = $value;
			}
			
		}
		parent::__construct( $result );
	}
	
	public function isValid()
	{
		return !(empty($this->date_creation) || empty($this->fk_MMC) || empty($this->fk_NNE));
	}
	
	/**
	 * @return mixed
	 */
	public function fk_MMC() {
		return $this->fk_MMC;
	}
	
	/**
	 * @param mixed $fk_MMC
	 */
	public function setFk_MMC( $fk_MMC ) {
		$this->fk_MMC = $fk_MMC;
	}
	
	/**
	 * @return mixed
	 */
	public function fk_NNE() {
		return $this->fk_NNE;
	}
	
	/**
	 * @param mixed $fk_NNE
	 */
	public function setFk_NNE( $fk_NNE ) {
		$this->fk_NNE = $fk_NNE;
	}
	
	/**
	 * @return mixed
	 */
	public function date_creation() {
		return $this->date_creation;
	}
	
	/**
	 * @param mixed $date_creation
	 */
	public function setDate_creation( $date_creation ) {
		$this->date_creation = $date_creation;
	}
	
}
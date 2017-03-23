<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 22/03/2017
 * Time: 15:17
 */

namespace Entity;


use OCFram\Entity;

class Newg extends Entity {
	
	protected $fk_NNC,
			$fk_NNE,
			$fk_MMC,
			$date_edition,
			$title,
			$content;
	
	const NNE_INVALID = 1;
	const NNE_VALID = 2;
	
	const CODE_TABLE = 'NNG';
	
	public function __construct( array $donnees = [] ) {
		$result = [];
		$simple_column = Newg::CODE_TABLE.'_';
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
		return !(empty($this->date_edition) || empty($this->fk_MMC) || empty($this->fk_NNE) || empty($this->title) || empty($this->content));
	}
	
	public function isEqual(Newg $Newg)
	{
		if($this->content == $Newg->content() && $this->title == $Newg->title())
			return true;
		else return false;
	}
	
	/**
	 * @return mixed
	 */
	public function fk_NNC() {
		return $this->fk_NNC;
	}
	
	/**
	 * @param mixed $fk_NNC
	 */
	public function setFk_NNC( $fk_NNC ) {
		$this->fk_NNC = $fk_NNC;
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
	public function date_edition() {
		return $this->date_edition;
	}
	
	/**
	 * @param mixed $date_edition
	 */
	public function setDate_edition( $date_edition ) {
		$this->date_edition = $date_edition;
	}
	
	/**
	 * @return mixed
	 */
	public function title() {
		return $this->title;
	}
	
	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}
	
	/**
	 * @return mixed
	 */
	public function content() {
		return $this->content;
	}
	
	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}
	
	
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:09
 */

namespace Entity;

use \OCFram\Entity;

class Commentc extends Entity implements \JsonSerializable {
	protected $fk_NCE, $fk_MMC, $fk_NNG, $visitor, $content, $date;
	const AUTEUR_INVALIDE  = 1;
	const CONTENU_INVALIDE = 2;
	const NCE_INVALID = 1;
	const NCE_VALID   = 2;
	const CODE_TABLE  = 'NCC';
	
	public function __construct( array $donnees = [] ) {
		
		$result = [];
		
		$simple_column = Commentc::CODE_TABLE . '_';
		foreach ( $donnees as $key => $value ) {
			
			$newkey = str_replace( $simple_column, '', $key );
			if ( !( strlen( $newkey ) == strlen( $key ) ) ) {
				if ( is_callable( [
					$this,
					$newkey,
				] ) ) {
					$result[ $newkey ] = $value;
				}
			}
			elseif ( is_callable( [
				$this,
				$key,
			] ) ) {
				$result[ $key ] = $value;
			}
		}
		parent::__construct( $result );
	}
	
	public function isValid() {
		return !( ( empty( $this->fk_MMC ) && empty( $this->visitor ) ) || empty( $this->content ) );
	}
	
	public function isEqual( Commentc $Commentc ) {
		if ( $this->content == $Commentc->content() ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * @return mixed
	 */
	public function fk_NCE() {
		return $this->fk_NCE;
	}
	
	/**
	 * @param mixed $fk_NCE
	 */
	public function setFk_NCE( $fk_NCE ) {
		$this->fk_NCE = $fk_NCE;
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
	public function fk_NNG() {
		return $this->fk_NNG;
	}
	
	/**
	 * @param mixed $fk_NNG
	 */
	public function setFk_NNG( $fk_NNG ) {
		$this->fk_NNG = $fk_NNG;
	}
	
	/**
	 * @return mixed
	 */
	public function visitor() {
		return $this->visitor;
	}
	
	/**
	 * @param mixed $visitor
	 */
	public function setVisitor( $visitor ) {
		$this->visitor = strip_tags( $visitor, PARENT::TAGS_ALLOWED );
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
		$this->content = strip_tags( $content, PARENT::TAGS_ALLOWED );
	}
	
	/**
	 * @return mixed
	 */
	public function date() {
		return $this->date;
	}
	
	/**
	 * @param mixed $date
	 */
	public function setDate( $date ) {
		$this->date = $date;
	}
	
	public function setMemberc( Memberc $Memberc  ) {
		$this->setReferences($Memberc, 'Memberc');
	}
	
	public function Memberc( ) {
		return $this->References(__FUNCTION__);
	}
	
	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->jsonSerializeCustom();
	}
}
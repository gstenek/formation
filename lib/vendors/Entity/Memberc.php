<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 12:21
 */

namespace Entity;

use \OCFram\Entity;

class Memberc extends Entity
{
	protected
		$name,
		$surname,
		$login,
		$email,
		$password,
		$dateInscription,
		$dateBirth,
		$fk_MMY,
		$fk_MME;

	
	const NAME_INVALIDE = 1;
	const SURNAME_INVALIDE = 2;
	const LOGIN_INVALIDE = 3;
	const EMAIL_INVALIDE = 4;
	
	const CODE_TABLE = 'MMC';
	
	const MME_INVALID = 1;
	const MME_VALID = 2;
	
	const MMY_ADMIN = 1;
	const MMY_BASIC = 2;
	
	public function __construct( array $donnees = [] ) {
		$result = [];
		
		$simple_column = Self::CODE_TABLE.'_';
		foreach ($donnees as $key => $value)
		{
			
			// MMC_fk_MME OR MMC_fk_MME_state OR  MMC_id
			$newkey = str_replace($simple_column,'',$key);
			if(!(strlen($newkey) == strlen($key))){
				if(is_callable([$this,$newkey]))
				{
					// si le champ n'est pas une classe
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
		return !(empty($this->name) || empty($this->surname) || empty($this->login) || empty($this->password) || empty($this->email));
	}
	
	// SETTERS
	
	/**
	 * @param mixed $id
	 */
	public function setid( $id ) {
		$this->id = $id;
	}
	
	/**
	 * @param mixed $name
	 */
	public function setname( $name ) {
		
		if (!is_string($name) || empty($name))
		{
			$this->erreurs[] = self::NAME_INVALIDE;
		}
		
		$this->name = strip_tags($name,PARENT::TAGS_ALLOWED);
	}
	
	/**
	 * @param mixed $surname
	 */
	public function setsurname( $surname ) {
		
		if (!is_string($surname) || empty($surname))
		{
			$this->erreurs[] = self::SURNAME_INVALIDE;
		}
		
		$this->surname = strip_tags($surname,PARENT::TAGS_ALLOWED);
	}
	
	/**
	 * @param mixed $login
	 */
	public function setlogin( $login ) {
		if (!is_string($login) || empty($login))
		{
			$this->erreurs[] = self::LOGIN_INVALIDE;
		}
		
		$this->login = strip_tags($login,PARENT::TAGS_ALLOWED);
	}
	
	/**
	 * @param mixed $password
	 */
	public function setpassword( $password ) {
		$this->password = strip_tags($password, PARENT::TAGS_ALLOWED);
	}
	
	/**
	 * @param mixed $email
	 */
	public function setemail( $email) {
		if (!is_string($email) || empty($email))
		{
			$this->erreurs[] = self::LOGIN_INVALIDE;
		}
		
		$this->email = strip_tags($email,PARENT::TAGS_ALLOWED);
	}
	
	/**
	 * @param mixed $dateInscription
	 */
	public function setdateInscription( $dateInscription ) {
		$this->dateInscription = $dateInscription;
	}
	
	/**
	 * @param mixed $dateBirth
	 */
	public function setdateBirth( $dateBirth ) {
		$this->dateBirth = $dateBirth;
	}
	
	// GETTERS
	
	/**
	 * @return mixed
	 */
	public function id() {
		return $this->id;
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function name() {
		return $this->name;
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function surname() {
		return $this->surname;
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function login() {
		return $this->login;
	}
	
	
	/**
	 * @return mixed
	 */
	public function email() {
		return $this->email;
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function password() {
		return $this->password;
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function dateInscription() {
		return $this->dateInscription;
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function dateBirth() {
		return $this->dateBirth;
	}
	
	
	/**
	 * @return mixed
	 */
	public function fk_MMY() {
		return $this->fk_MMY;
	}
	
	/**
	 * @return bool
	 */
	public function isTypeAdmin() {
		return $this->fk_MMY() == self::MMY_ADMIN;
	}
	
	/**
	 * @param mixed $fk_MMY
	 */
	public function setFk_MMY( $fk_MMY ) {
		$this->fk_MMY = $fk_MMY;
	}
	
	/**
	 * @return mixed
	 */
	public function fk_MME() {
		return $this->fk_MME;
	}
	
	/**
	 * @param mixed $fk_MME
	 */
	public function setFk_MME( $fk_MME ) {
		$this->fk_MME = $fk_MME;
	}
	
	
	
	
}
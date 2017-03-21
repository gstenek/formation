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
		$dateBirth;
	
	const NAME_INVALIDE = 1;
	const SURNAME_INVALIDE = 2;
	const LOGIN_INVALIDE = 3;
	const EMAIL_INVALIDE = 4;
	const CODE_TABLE = 'MMC';
	
	public function __construct( array $donnees = [] ) {
		$result = [];
		
		$fk_column = Memberc::CODE_TABLE.'_fk_';
		$simple_column = Memberc::CODE_TABLE.'_';
		foreach ($donnees as $key => $value)
		{
			
			// MMC_fk_MME OR MMC_fk_MME_state OR  MMC_id
			if(!(strpos($fk_column, $key) === false))
			{
				$result[str_replace(Memberc::CODE_TABLE.'_fk_','',$key)] = $value;
				
			}elseif(!(strpos($simple_column, $key))){
					$result[str_replace(Memberc::CODE_TABLE.'_','',$key)] = $value;
			}else{
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
		
		$this->name = $name;
	}
	
	/**
	 * @param mixed $surname
	 */
	public function setsurname( $surname ) {
		
		if (!is_string($surname) || empty($surname))
		{
			$this->erreurs[] = self::SURNAME_INVALIDE;
		}
		
		$this->surname = $surname;
	}
	
	/**
	 * @param mixed $login
	 */
	public function setlogin( $login ) {
		if (!is_string($login) || empty($login))
		{
			$this->erreurs[] = self::LOGIN_INVALIDE;
		}
		
		$this->login = $login;
	}
	
	/**
	 * @param mixed $password
	 */
	public function setpassword( $password ) {
		$this->password = $password;
	}
	
	/**
	 * @param mixed $email
	 */
	public function setemail( $email) {
		if (!is_string($email) || empty($email))
		{
			$this->erreurs[] = self::LOGIN_INVALIDE;
		}
		
		$this->email = $email;
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
	
	
	
	
}
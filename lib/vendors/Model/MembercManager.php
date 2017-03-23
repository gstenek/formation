<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 12:48
 */

namespace Model;

use \OCFram\Manager;
use \Entity\Memberc;

abstract class MembercManager extends Manager{
	/**
	 * Méthode permettant d'ajouter un member
	 *
	 * @param Memberc $Memberc
	 *
	 * @return void
	 * @internal param Le $member member à ajouter
	 */
	abstract protected function insertMemberc(Memberc $Memberc);
	
	/**
	 * Méthode permettant d'obtenir un member spécifique.
	 *
	 * @param $login L'identifiant du member
	 *
	 * @return Memberc
	 */
	abstract public function getMembercUsingLogin($login);
	
	/**
	 * Méthode permettant d'obtenir un member spécifique.
	 *
	 * @param $id
	 *
	 * @return Memberc
	 * @internal param L'identifiant du member
	 *
	 */
	abstract public function getMembercUsingId($id);
	
	/**
	 * Méthode permettant d'enregistrer un membre.
	 *
	 * @param Memberc $Memberc
	 *
	 * @return void
	 * @internal param News $news la news à enregistrer
	 * @see      self::add()
	 * @see      self::modify()
	 */
	public function save(Memberc $Memberc)
	{
		if ($Memberc->isValid())
		{
			$Memberc->setpassword(password_hash($Memberc->password(), PASSWORD_BCRYPT));
			
			//$Memberc->isNew() ? $this->insertMemberc($Memberc) : $this->modify($Memberc); // si update prévu
			$this->insertMemberc($Memberc);
		}
		else
		{
			throw new \RuntimeException('Le membre doit être validée pour être enregistrée');
		}
	}
	
}
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
	 * @param $member Le member à ajouter
	 * @return void
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
	 * Méthode permettant d'enregistrer un membre.
	 * @param $news News la news à enregistrer
	 * @see self::add()
	 * @see self::modify()
	 * @return void
	 */
	public function save(Memberc $Memberc)
	{
		if ($Memberc->isValid())
		{
			//$Memberc->isNew() ? $this->insertMemberc($Memberc) : $this->modify($Memberc); // si update prévu
			$this->insertMemberc($Memberc);
		}
		else
		{
			throw new \RuntimeException('Le membre doit être validée pour être enregistrée');
		}
	}
	
}
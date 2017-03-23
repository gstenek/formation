<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 12:48
 */

namespace Model;


use Entity\Memberc;

class MembercManagerPDO extends MembercManager {
	
	protected function insertMemberc(Memberc $Memberc) {
		
		$q = $this->dao->prepare('INSERT INTO t_mem_memberc(MMC_name, MMC_surname, MMC_login, MMC_email, MMC_password, MMC_dateInscription, MMC_dateBirth) VALUES (:name,:surname, :login, :email, :password, :dateInscription, :dateBirth)');
		
		if($Memberc->dateInscription() == NULL)
		{
			$Memberc->setdateInscription(date("Y-m-d H:i:s"));
		}
		
		if($Memberc->dateBirth() == NULL)
		{
			$Memberc->setdateBirth(date("Y-m-d"));
		}
		
		$q->bindValue(':name', $Memberc->name());
		$q->bindValue(':surname', $Memberc->surname());
		$q->bindValue(':login', $Memberc->login());
		$q->bindValue(':email', $Memberc->email());
		$q->bindValue(':password', $Memberc->password());
		$q->bindValue(':dateBirth', $Memberc->dateBirth());
		$q->bindValue(':dateInscription', $Memberc->dateInscription());
		
		$q->execute();
		
		$Memberc->setId($this->dao->lastInsertId());
	}
	
	public function getMembercUsingLogin( $login ) {
		
		$q = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_surname, MMC_login, MMC_email,  MMC_password, MMC_dateInscription, MMC_dateBirth FROM t_mem_memberc WHERE MMC_login = :login');
		$q->bindValue(':login', $login);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$result = $q->fetch();
		
		if($result == false)
		{
			return false;
		}else{
			$Memberc = new Memberc($result);
			return $Memberc;
		}
		
	}
	
	public function getMembercUsingEmail( $email ) {
		
		$q = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_surname, MMC_login, MMC_email,  MMC_password, MMC_dateInscription, MMC_dateBirth FROM t_mem_memberc WHERE MMC_email = :email');
		$q->bindValue(':email', $email);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$result = $q->fetch();
		
		if($result == false)
		{
			return false;
		}else{
			$Memberc = new Memberc($result);
			return $Memberc;
		}
		
	}
	
	/**
	 * Méthode permettant d'obtenir un member spécifique.
	 *
	 * @param $id
	 *
	 * @return Memberc
	 * @internal param L'identifiant du member
	 *
	 */
	public function getMembercUsingId( $id ) {
		
		$q = $this->dao->prepare('SELECT MMC_id, MMC_name, MMC_surname, MMC_login, MMC_email,  MMC_password, MMC_dateInscription, MMC_dateBirth FROM t_mem_memberc WHERE MMC_id = :id');
		$q->bindValue(':id', $id);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$result = $q->fetch();
		
		if($result == false)
		{
			return false;
		}else{
			$Memberc = new Memberc($result);
			return $Memberc;
		}
		
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:33
 */

namespace Model;

use Entity\Memberc;
use Entity\Newc;
use \Entity\Newg;

class NewgManagerPDO extends NewgManager
{
	/**
	 * Méthode retournant une news précise.
	 *
	 *
	 * @param $newg_id
	 *
	 * @return Newg|false La news demandée
	 *
	 * @internal param $newg_id
	 *
	 * @internal param L $newg_id 'identifiant de la news à récupérer
	 */
	public function getNewgUsingNewgId( $newg_id ) {
		$q = $this->dao->prepare('SELECT  *
								FROM t_new_newg
								INNER JOIN t_new_newc ON NNC_id = NNG_fk_NNC
								INNER JOIN t_mem_memberc ON MMC_id = NNG_fk_MMC
								WHERE NNG_id = :id ');
		$q->bindValue(':id', $newg_id);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$result = $q->fetch();
		
		if($result == false)
		{
			return false;
		}else{
			$Newg = new Newg($result);
			$Newg->setMemberc(new Memberc($result));
			$Newg->setNewc(new Newc($result));
			return $Newg;
		}
		
	}
	
	/**
	 * Méthode retournant une news précise.
	 *
	 * @param $newc_id
	 *
	 * @return Newg La news demandée
	 * @internal param $Newg_id
	 *
	 * @internal param L $newg_id 'identifiant de la news à récupérer
	 */
	public function getNewgValidUsingNewcId( $newc_id ) {
		$q = $this->dao->prepare('SELECT  * 
								FROM t_new_newg 
								INNER JOIN t_new_newc ON NNG_fk_NNC = NNC_id 
								INNER JOIN t_mem_memberc ON NNG_fk_MMC = MMC_id
								WHERE NNG_fk_NNC = :id 
								AND NNG_fk_NNE = :NNE');
		$q->bindValue(':id', $newc_id);
		$q->bindValue(':NNE', Newg::NNE_VALID);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$result = $q->fetch();
		
		if($result == false)
		{
			return false;
		}else{
			$Newg = new Newg($result);
			$Newg->setNewc(new Newc($result));
			$Newg->setMemberc(new Memberc($result));
			//$Newg->setFk_NNC(new Newc($result));
			return $Newg;
		}
		
	}
	
	/**
	 * Méthode permettant d'ajouter une news.
	 *
	 * @param Newg $Newg
	 *
	 * @return void
	 * @internal param Newg $newg La news à ajouter
	 *
	 */
	protected function insertNewg( Newg $Newg ) {
		
		$requete = $this->dao->prepare('INSERT INTO t_new_newg(NNG_fk_NNC, NNG_fk_NNE, NNG_fk_MMC, NNG_date_edition, NNG_title, NNG_content) VALUES (:NNC, :etat, :auteur, :date_edition, :title, :_content)');
		
		
		$requete->bindValue(':NNC', $Newg->fk_NNC());
		$requete->bindValue(':etat', $Newg->fk_NNE());
		$requete->bindValue(':auteur', $Newg->fk_MMC());
		$requete->bindValue(':date_edition', $Newg->date_edition());
		$requete->bindValue(':title', $Newg->title());
		$requete->bindValue(':_content', $Newg->content());
		
		$requete->execute();
		
		$Newg->setId($this->dao->lastInsertId());
		
	}
	
	/**
	 * Méthode permettant de supprimer une news.
	 *
	 * @param $newg_id
	 * @param $newg_nne
	 *
	 * @return void
	 * @internal param int $id L'identifiant de la news à supprimer
	 *
	 */
	public function updatefk_NNEOfNewgUsingNewgIdAndNNE( $newg_id, $newg_nne ) {
		$requete = $this->dao->prepare('UPDATE t_new_newg SET NNG_fk_NNE = :NNE WHERE NNG_id = :id');
		
		$requete->bindValue(':id', $newg_id, \PDO::PARAM_INT);
		$requete->bindValue(':NNE', $newg_nne);
		
		$requete->execute();
	}
	
}
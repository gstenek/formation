<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:33
 */

namespace Model;

use Entity\Memberc;
use \Entity\Newc;
use Entity\Newg;

class NewcManagerPDO extends NewcManager
{
	/**
	 * Méthode retournant une liste de news demandées
	 *
	 * @param $debut  int La première news à sélectionner
	 * @param $limite int Le nombre de news à sélectionner
	 * @param $newc_nne int Etat des news demandées
	 *
	 * @return Newg[] La liste des news. Chaque entrée est une instance de News.
	 */
	public function getNewsListUsingNNE( $debut = -1, $limite = -1, $newc_nne ) {
		
		$query = 'SELECT * 
					FROM t_new_newc	
					INNER JOIN t_new_newg ON NNC_id = NNG_fk_NNC 
					INNER JOIN t_mem_memberc ON NNC_fk_MMC = MMC_id 
					WHERE NNC_fk_NNE = :NNC_fk_NNE 
					AND NNG_fk_NNE = :NNG_fk_NNE 
					ORDER BY NNG_date_edition DESC';
		
		if ($debut != -1 || $limite != -1)
		{
			$query .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$q = $this->dao->prepare($query);
		$q->bindValue(':NNC_fk_NNE', $newc_nne);
		$q->bindValue(':NNG_fk_NNE', Newg::NNE_VALID);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		
		$result = $q->fetchAll();
		$news = [];
		foreach ( $result as $key => $line ) {
			
			$Newg = new Newg($line);
			$Newg->setMemberc(new Memberc($line));
			$Newg->setNewc(new Newc($line));
			
			$news[] = $Newg;
		}
		return $news;
	}
	
	/**
	 * Méthode retournant une news précise.
	 * @param $newc_id int L'identifiant de la news à récupérer
	 * @return Newc|bool La news demandée
	 */
	public function getNewcUsingNewcId( $newc_id ) {
		$q = $this->dao->prepare('SELECT NNC_fk_MMC, NNC_fk_NNE, NNC_date_creation 
								FROM t_new_newc 
								WHERE NNC_id = :id 
								AND NNC_fk_NNE = :NNE');
		$q->bindValue(':id', $newc_id);
		$q->bindValue(':NNE', Newc::NNE_VALID);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$Newc = false;
		if($result = $q->fetch())
		{
			$Newc = new Newc($result);
			$Newc->setId($newc_id);
		}
		return $Newc;
	}
	
	/**
	 * Méthode renvoyant le nombre de news total ayant l'état précisé.
	 *
	 * @param      $newc_nne
	 *
	 *
	 * @return int
	 */
	public function countNewcUsingNNE($newc_nne) {
		
		return $this->dao->query('SELECT COUNT(*) 
								FROM t_new_newc 
								WHERE NNC_fk_NNE ='.$newc_nne)->fetchColumn();
	}
	
	/**
	 * @param Newc $Newc
	 *
	 * Insertion d'une Newc
	 */
	public function insertNewc( Newc $Newc ) {
		$requete = $this->dao->prepare('INSERT INTO t_new_newc(NNC_fk_MMC, NNC_fk_NNE, NNC_date_creation) 
										VALUES (:auteur, :etat, :date_creation)');
		
		$requete->bindValue(':etat', $Newc->fk_NNE());
		$requete->bindValue(':auteur', $Newc->fk_MMC());
		$requete->bindValue(':date_creation', $Newc->date_creation());
		
		$requete->execute();
		
		$Newc->setId($this->dao->lastInsertId());
	}

	
	/**
	 * Méthode permettant de changer l'état d'une news.
	 *
	 * @param $newc_id
	 * @param $newc_nne
	 *
	 * @return void
	 * @internal param int $id L'identifiant de la news à supprimer
	 */
	public function updatefk_NNEOfNewcUsingNewcIdAndNNE( $newc_id, $newc_nne ) {
		$requete = $this->dao->prepare('UPDATE t_new_newc 
										SET NNC_fk_NNE = :NNE 
										WHERE NNC_id = :id');
		
		$requete->bindValue(':id', $newc_id, \PDO::PARAM_INT);
		$requete->bindValue(':NNE', $newc_nne);
		
		$requete->execute();
	}
	
	/**
	 * Méthode retournant une news demandée
	 *
	 * @param $newc_id
	 *
	 * @return Newg Une instance de News.
	 * @internal param int $debut La première news à sélectionner
	 * @internal param int $limite Le nombre de news à sélectionner
	 * @internal param $newc_id
	 *
	 */
	public function getNewsUsingNewcId( $newc_id ) {
		$q = $this->dao->prepare('SELECT MMC_id, MMC_login, NNG_id, NNG_title, NNG_content, NNG_date_edition, NNC_id, NNC_date_creation
									FROM t_new_newc
									INNER JOIN t_new_newg ON NNC_id = NNG_fk_NNC
									INNER JOIN t_mem_memberc ON NNC_fk_MMC = MMC_id
									WHERE NNC_id = :id
									AND NNG_fk_NNE = :NNG_fk_NNE');
		$q->bindValue(':id', $newc_id);
		$q->bindValue(':NNG_fk_NNE', Newg::NNE_VALID);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC | \PDO::FETCH_PROPS_LATE);
		$result = $q->fetch();
		$Memberc = new Memberc(['MMC_id' => $result['MMC_id'],
								'MMC_login' => $result['MMC_login']]);
		$Newg = new Newg(['NNG_id' => $result['NNG_id'],
						  'NNG_title' => $result['NNG_title'],
						  'NNG_date_edition' => $result['NNG_date_edition'],
						  'NNG_content' => $result['NNG_content']]);
		$Newc = new Newc(['NNC_id' => $result['NNC_id'],
						  'NNC_date_creation' => $result['NNC_date_creation']]);
		$Newg->setFk_MMC($Memberc);
		$Newg->setFk_NNC($Newc);
		
		return $Newg;
	}
}
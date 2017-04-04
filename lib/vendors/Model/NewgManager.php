<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:32
 */

namespace Model;

use Entity\Newc;
use \OCFram\Manager;
use \Entity\Newg;
use OCFram\ManagerData;

abstract class NewgManager extends ManagerData {
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
	abstract public function getNewgValidUsingNewcId( $newc_id );
	
	/**
	 * Méthode permettant d'ajouter une news.
	 *
	 * @param Newg $Newg
	 *
	 * @return int
	 * @internal param Newg $newg La news à ajouter
	 *
	 */
	abstract protected function insertNewg( Newg $Newg );
	
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
	abstract protected function getNewgUsingNewgId($newg_id);
	
	/**
	 * Méthode permettant d'enregistrer une news.
	 *
	 * @param Newg $Newg
	 *
	 * @return void
	 * @internal param Newg $newg la news à enregistrer
	 *
	 * @see      self::add()
	 * @see      self::modify()
	 */
	public function save( Newg $Newg ) {
		if($Newg->isValid()){
			if ( $Newg->fk_NNC() === null ) { // si on insert une nouvelle news
				
				// insertion d'un newc
				$Newc = new Newc(['fk_MMC' => $Newg->fk_MMC(),
								  'fk_NNE' => Newc::NNE_VALID,
								  'date_creation' => $Newg->date_edition()]);
				$this->Managers->getManagerOf( 'Newc' )->insertNewc($Newc);
				$Newg->setFk_NNC( $Newc->id());
			}else{ // si on edit une news
				// L'id actuelle de newg est l'id de la newg à rendre invalide
				$this->Managers->getManagerOf( 'Newg' )->updatefk_NNEOfNewgUsingNewgIdAndNNE($Newg->id(), Newg::NNE_INVALID);
				
			}
			// A l'insertion, $Newg récupère l'id de la dernière Newg insérée
			$this->insertNewg($Newg);
		}else{
			throw new \RuntimeException( 'La news doit être valide pour être enregistrée' );
		}
		
		
		

	}
	
	/**
	 * Méthode permettant de supprimer une news.
	 *
	 * @param $Newg_id
	 * @param $Newg_nne
	 *
	 * @return void
	 * @internal param int $id L'identifiant de la news à supprimer
	 *
	 */
	abstract public function updatefk_NNEOfNewgUsingNewgIdAndNNE( $Newg_id, $Newg_nne );
}
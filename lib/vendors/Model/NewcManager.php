<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:32
 */

namespace Model;

use Entity\Newg;
use \OCFram\Manager;
use \Entity\Newc;

abstract class NewcManager extends Manager
{
	/**
	 * Méthode retournant une liste de news demandée
	 *
	 * @param $debut  int La première news à sélectionner
	 * @param $limite int Le nombre de news à sélectionner
	 * @param $newc_nne
	 *
	 * @return array La liste des news. Chaque entrée est une instance de News.
	 */
	abstract public function getNewsListUsingNNE($debut = -1, $limite = -1, $newc_nne);
	
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
	abstract public function getNewsUsingNewcId($newc_id);
	
	/**
	 * Méthode retournant une news précise.
	 * @param $id int L'identifiant de la news à récupérer
	 * @return Newc La news demandée
	 */
	abstract public function getNewcUsingNewcId($newc_id);
	
	/**
	 * Méthode renvoyant le nombre de news total.
	 *
	 * @param $newc_nne
	 *
	 * @return int
	 */
	abstract public function countNewcUsingNNE($newc_nne);
	
	/**
	 * Méthode permettant d'ajouter une news.
	 *
	 * @param Newc $Newc
	 *
	 * @return void
	 * @internal param Newc $news La news à ajouter
	 */
	abstract public function insertNewc(Newc $Newc);
	
	/**
	 * Méthode permettant de modifier une news.
	 *
	 * @param Newc $Newc
	 *
	 * @return void
	 * @internal param Newc $news la news à modifier
	 */
	abstract protected function modify(Newc $Newc);
	
	/**
	 * Méthode permettant de supprimer une news.
	 *
	 * @param $newc_id
	 * @param $newc_nne
	 *
	 * @return void
	 * @internal param int $id L'identifiant de la news à supprimer
	 */
	abstract public function updatefk_NNEOfNewcUsingNewcIdAndNNE($newc_id, $newc_nne);
}
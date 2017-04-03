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
	 * Méthode retournant une liste de news demandées
	 *
	 * @param $debut  int La première news à sélectionner
	 * @param $limite int Le nombre de news à sélectionner
	 * @param $newc_nne int Etat des news demandées
	 *
	 * @return Newg[] La liste des news. Chaque entrée est une instance de News.
	 */
	abstract public function getNewsListUsingNNE($debut = -1, $limite = -1, $newc_nne);
	
	/**
	 * Méthode retournant une news demandée
	 *
	 * @param $newc_id int
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
	 * @param $newc_id int L'identifiant de la news à récupérer
	 * @return Newc|bool La news demandée
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
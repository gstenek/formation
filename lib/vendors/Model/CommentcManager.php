<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:09
 */

namespace Model;

use MongoDB\Driver\Exception\RuntimeException;
use \OCFram\Manager;
use \Entity\Commentc;
use OCFram\ManagerData;

abstract class CommentcManager extends ManagerData
{
	/**
	 * Méthode permettant d'ajouter un commentaire
	 *
	 * @param Commentc $Commentc Le commentaire à ajouter
	 *
	 * @return void
	 */
	abstract protected function insertCommentc(Commentc $Commentc);
	
	/**
	 * Méthode permettant d'enregistrer un commentaire.
	 * @param Commentc $Commentc Le commentaire à enregistrer
	 * @return Commentc|RuntimeException
	 */
	public function save(Commentc $Commentc)
	{
		if ($Commentc->isValid())
		{
			$Commentc->isNew() ? $this->insertCommentc($Commentc) : $this->updateCommentc($Commentc);
			
		}
		else
		{
			throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');
		}
	}
	
	/**
	 * Méthode permettant de récupérer une liste de commentaires.
	 *
	 * @param $newc_id
	 *
	 * @return Commentc[]
	 * @internal param La $news news sur laquelle on veut récupérer les commentaires
	 */
	abstract public function  getCommentcListUsingNewcId($newc_id);
	
	/**
	 * @param $newc_id
	 * @param $commentc_id
	 *
	 * @return mixed
	 */
	abstract public function  getLastCommentcListUsingNewcIdAndCommentcId($newc_id, $commentc_id);
	
	
	/**
	 * Méthode permettant de modifier un commentaire.
	 * @param Commentc $Commentc commentaire à modifier
	 * @return void
	 */
	abstract protected function updateCommentc(Commentc $Commentc);
	
	/**
	 * Méthode permettant d'obtenir un commentaire spécifique.
	 *
	 * @param $commentc_id
	 *
	 * @return Commentc
	 * @internal param L $id 'identifiant du commentaire
	 *
	 */
	abstract public function getCommentcUsingCommentcId($commentc_id);
	
	
	
	
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:09
 */

namespace Model;

use \OCFram\Manager;
use \Entity\Commentc;

abstract class CommentcManager extends Manager
{
	/**
	 * Méthode permettant d'ajouter un commentaire
	 *
	 * @param Commentc|Le $Commentc Le commentaire à ajouter
	 *
	 * @return void
	 */
	abstract protected function insertCommentc(Commentc $Commentc);
	
	/**
	 * Méthode permettant d'enregistrer un commentaire.
	 * @param $Commentc Le commentaire à enregistrer
	 * @return void
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
	 * @return array
	 * @internal param La $news news sur laquelle on veut récupérer les commentaires
	 */
	abstract public function  getCommentcListUsingNewcId($newc_id);
	
	
	/**
	 * Méthode permettant de modifier un commentaire.
	 * @param $Commentc Le commentaire à modifier
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
	
	/**
	 * Méthode permettant de supprimer un commentaire.
	 * @param $id L'identifiant du commentaire à supprimer
	 * @return void
	 */
	abstract public function delete($id);
	
	/**
	 * Méthode permettant de supprimer tous les commentaires liés à une news
	 * @param $news L'identifiant de la news dont les commentaires doivent être supprimés
	 * @return void
	 */
	abstract public function deleteFromNews($news);
	
	
}
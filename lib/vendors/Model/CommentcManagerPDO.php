<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:09
 */

namespace Model;

use \Entity\Commentc;

class CommentcManagerPDO extends CommentcManager
{
	public function getCommentcListUsingNewcId($newc_id)
	{
		
		$q = $this->dao->prepare('SELECT NCC_id, NCC_fk_NCE, NCC_fk_MMC, NCC_content, NCC_visitor, NCC_date, NCC_fk_NNG
									FROM t_new_commentc
									INNER JOIN t_new_newg ON NCC_fk_NNG = NNG_id
									INNER JOIN t_new_newc ON NNC_id = NNG_fk_NNC
									WHERE NNC_id = :NNC
									GROUP BY NCC_id');
		$q->bindValue(':NNC', $newc_id, \PDO::PARAM_INT);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_ASSOC );
		
		$result = $q->fetchAll();
		$Comments = [];
		foreach ($result as $key => $Commentc)
		{
			
			$Comments[] = new Commentc($Commentc);
		}
		
		return $Comments;
	}
	
	
	/**
	 * Méthode permettant d'ajouter un commentaire
	 *
	 * @param Commentc|Le $Commentc Le commentaire à ajouter
	 *
	 * @return void
	 */
	protected function insertCommentc( Commentc $Commentc ) {
		
		$q = $this->dao->prepare('INSERT INTO t_new_commentc (NCC_fk_NCE, NCC_fk_MMC, NCC_content, NCC_visitor, NCC_date, NCC_fk_NNG)
									VALUES (:NCE, :MMC, :content, :visitor, :date_comment, :NNG)');
		
		$q->bindValue(':NCE', $Commentc->fk_NCE(), \PDO::PARAM_INT);
		$q->bindValue(':MMC', $Commentc->fk_MMC(), \PDO::PARAM_INT);
		$q->bindValue(':content', $Commentc->content());
		$q->bindValue(':visitor', $Commentc->visitor());
		$q->bindValue(':date_comment', $Commentc->date());
		$q->bindValue(':NNG', $Commentc->fk_NNG(), \PDO::PARAM_INT);
		
		$q->execute();
		
		$Commentc->setId($this->dao->lastInsertId());
	}
	
	protected function updateCommentc(Commentc $Commentc)
	{
		$q = $this->dao->prepare('UPDATE t_new_commentc
									SET NCC_fk_NCE = :NCE,
									NCC_fk_MMC = :MMC,
									NCC_content = :content,
									NCC_visitor = :visitor,
									NCC_date = :date_comment,
									NCC_fk_NNG = :NNG
									WHERE NCC_id = :id');
		
		$q->bindValue(':content', $Commentc->content());
		$q->bindValue(':id', $Commentc->id(), \PDO::PARAM_INT);
		$q->bindValue(':NNG', $Commentc->fk_NNG(), \PDO::PARAM_INT);
		$q->bindValue(':MMC', $Commentc->fk_MMC(), \PDO::PARAM_INT);
		$q->bindValue(':NCE', $Commentc->fk_NCE(), \PDO::PARAM_INT);
		$q->bindValue(':date_comment', $Commentc->date());
		$q->bindValue(':visitor', $Commentc->visitor());
		
		$q->execute();
	}
	
	public function get($id)
	{
		$q = $this->dao->prepare('SELECT id, news, auteur, contenu FROM comments WHERE id = :id');
		$q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Commentc' );
		
		return $q->fetch();
	}
	
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM comments WHERE id = '.(int) $id);
	}
	
	public function deleteFromNews($news)
	{
		$this->dao->exec('DELETE FROM comments WHERE news = '.(int) $news);
	}
	
}
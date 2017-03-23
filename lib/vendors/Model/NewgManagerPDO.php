<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 11:33
 */

namespace Model;

use \Entity\Newg;

class NewgManagerPDO extends NewgManager
{
	
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
		$q = $this->dao->prepare('SELECT  NNG_id, NNG_fk_MMC, NNG_fk_NNE, NNG_date_edition, NNG_title, NNG_content, NNG_fk_NNC FROM t_new_newg WHERE NNG_fk_NNC = :id AND NNG_fk_NNE = :NNE');
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
	
	/*public function getList($debut = -1, $limite = -1)
	{
		$sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news ORDER BY id DESC';
		
		if ($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$requete = $this->dao->query($sql);
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
		
		$listeNews = $requete->fetchAll();
		
		foreach ($listeNews as $news)
		{
			$news->setDateAjout(new \DateTime($news->dateAjout()));
			$news->setDateModif(new \DateTime($news->dateModif()));
		}
		
		$requete->closeCursor();
		
		return $listeNews;
	}
	
	public function getUnique($id)
	{
		$requete = $this->dao->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id = :id');
		$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$requete->execute();
		
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
		
		if ($news = $requete->fetch())
		{
			$news->setDateAjout(new \DateTime($news->dateAjout()));
			$news->setDateModif(new \DateTime($news->dateModif()));
			
			return $news;
		}
		
		return null;
	}
	
	public function count()
	{
		return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
	}
	
	protected function add(News $news)
	{
		$requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');
		
		$requete->bindValue(':titre', $news->titre());
		$requete->bindValue(':auteur', $news->auteur());
		$requete->bindValue(':contenu', $news->contenu());
		
		$requete->execute();
	}
	
	protected function modify(News $news)
	{
		$requete = $this->dao->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');
		
		$requete->bindValue(':titre', $news->titre());
		$requete->bindValue(':auteur', $news->auteur());
		$requete->bindValue(':contenu', $news->contenu());
		$requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);
		
		$requete->execute();
	}
	
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM news WHERE id = '.(int) $id);
	}*/
	
}
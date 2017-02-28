<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 15:53
 */

namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class NewsController extends BackController
{
	public function executeIndex(HTTPRequest $request)
	{
		
		$nombreNews = $this->app->config()->get('nombre_news');
		$nombreCaracteres = $this->app->config()->get('nombre_caracteres');
		
		// On ajoute une définition pour le titre.
		$this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');
		
		// On récupère le manager des news.
		$manager = $this->managers->getManagerOf('News');
		
		// Cette ligne, vous ne pouviez pas la deviner sachant qu'on n'a pas encore touché au modèle.
		// Contentez-vous donc d'écrire cette instruction, nous implémenterons la méthode ensuite.
		$listeNews = $manager->getList(0, $nombreNews);
		
		foreach ($listeNews as $news)
		{
			if (strlen($news->contenu()) > $nombreCaracteres)
			{
				$debut = substr($news->contenu(), 0, $nombreCaracteres);
				$debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
				
				$news->setContenu($debut);
			}
		}
		
		
		// On ajoute la variable $listeNews à la vue.
		$this->page->addVar('listeNews', $listeNews);
	}
}
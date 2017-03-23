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
use \Entity\Comment;
use \Entity\Newc;
use \OCFram\Form;
use \OCFram\StringField;
use \OCFram\TextField;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;

class NewsController extends BackController
{
	public function executeIndex(HTTPRequest $request)
	{
		
		$nombreNews = $this->app->config()->get('nombre_news');
		$nombreCaracteres = $this->app->config()->get('nombre_caracteres');
		
		// On ajoute une définition pour le titre.
		$this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');
		
		// On récupère le manager des news.
		$manager = $this->managers->getManagerOf('Newc');
		
		// Cette ligne, vous ne pouviez pas la deviner sachant qu'on n'a pas encore touché au modèle.
		// Contentez-vous donc d'écrire cette instruction, nous implémenterons la méthode ensuite.
		$listeNews = $manager->getNewsListUsingNNE(-1,-1,Newc::NNE_VALID);
		
		foreach ($listeNews as $news)
		{
			if (strlen($news->content()) > $nombreCaracteres)
			{
				$debut = substr($news->content(), 0, $nombreCaracteres);
				$debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
				
				$news->setContent($debut);
			}
		}
		
		
		// On ajoute la variable $listeNews à la vue.
		$this->page->addVar('listeNews', $listeNews);
	}
	
	public function executeBuildNewsDetail(HTTPRequest $request)
	{
		
		$Newg = $this->managers->getManagerOf('Newc')->getNewsUsingNewcId($request->getData('id'));
		
		if (empty($Newg))
		{
			$this->app->httpResponse()->redirect404();
		}
		
		$this->page->addVar('title', $Newg->title());
		$this->page->addVar('Newg', $Newg);
		$this->page->addVar('comments', array()/*$this->managers->getManagerOf('Comments')->getListOf($Newg->id())*/);
	}
	
	public function executeInsertComment(HTTPRequest $request)
	{
		// Si le formulaire a été envoyé.
		if ($request->method() == 'POST')
		{
			$comment = new Comment([
				'news' => $request->getData('news'),
				'auteur' => $request->postData('auteur'),
				'contenu' => $request->postData('contenu')
			]);
		}
		else
		{
			$comment = new Comment;
		}
		
		$formBuilder = new CommentFormBuilder($comment);
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
		$formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
			$this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
		}
		
		$this->page->addVar('comment', $comment);
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('title', 'Ajout d\'un commentaire');
	}
}
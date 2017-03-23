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
use \Entity\Commentc;
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
		$comments = $this->managers->getManagerOf('Commentc')->getCommentcListUsingNewcId($Newg->fk_NNC()->id());
		
		$this->page->addVar('title', $Newg->title());
		$this->page->addVar('Newg', $Newg);
		$this->page->addVar('comments', $comments);
	}
	
	public function executeBuildCommentForm(HTTPRequest $request)
	{
		if($request->postData('submit')) {
			$this->executePutCommentc($request);
		}else{
			
			$formBuilder = new CommentFormBuilder(new Commentc(),$this);
			$formBuilder->build();
			
			$form = $formBuilder->form();
			
			$this->page->addVar('title', 'Ajout d\'un commentaire');
			$this->page->addVar('form', $form->createView());
			$this->page->addVar('submit', 'Valider');
			$this->page->addVar('action', '');
		}
		
	}
	
	public function executePutCommentc(HTTPRequest $request){
		$Newg = $this->managers->getManagerOf('Newg')->getNewgValidUsingNewcId($request->getData('news'));
		$Commentc = new Commentc([
			'content' => $request->postData('content'),
			'fk_NCE' => Commentc::NCE_VALID,
			'date' => date("Y-m-d H:i:s"),
			'fk_NNG' => $Newg->id()
		]);
		if($this->app()->user()->isAuthenticated()){
			$Commentc->setFk_MMC($this->app()->user()->isAuthenticated()->getAttribute('Memberc')->id());
		}else{
			$Commentc->setVisitor($request->postData('visitor'));
		}
		
		$formBuilder = new CommentFormBuilder($Commentc,$this);
		$formBuilder->build();
		$form = $formBuilder->form();
		
		// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
		$formHandler = new FormHandler($form, $this->managers->getManagerOf('Commentc'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Merci pour votre commentaire !');
			$this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
		}
		
		$this->page->addVar('title', 'Ajout d\'un commentaire');
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('submit', 'Valider');
		$this->page->addVar('action', '');
	}

	
}
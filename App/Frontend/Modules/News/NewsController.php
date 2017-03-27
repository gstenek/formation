<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 15:53
 */

namespace App\Frontend\Modules\News;

use Entity\Memberc;
use Entity\Newg;
use FormBuilder\NewsFormBuilder;
use Model\NewcManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Commentc;
use \Entity\Newc;
use \OCFram\Form;
use OCFram\RouterFactory;
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
		$this->page->addVar('title', 'Liste des dernières news');
		
		// On récupère le manager des news.
		/** @var NewcManager $manager */
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
	
	public function executeBuildNews( HTTPRequest $request ) {
		if(!$this->app()->user()->isAuthenticated()){
			$this->app->user()->setFlash('Connectez vous pour profiter des fonctionnalités.');
			$this->app->httpResponse()->redirect('/login');
		}
		
		if($request->getExists( 'id' )){ // L'identifiant de la news est transmis si on veut la modifier
			/** @var NewcManager $NewcManager */
			$NewcManager = $this->managers->getManagerOf( 'Newc' );
			/** @var Newc $Newc */
			$Newc = $NewcManager->getNewcUsingNewcId( $request->getData( 'id' ) );
			if (false === $Newc) {
				$this->app->httpResponse()->redirect(self::getLinkToIndex());
			}
			
			/** @var Memberc $Memberc */
			$Memberc = $this->app()->user()->getAttribute('Memberc');
			if($Memberc->id() == $Newc->fk_MMC() || $Memberc->isTypeAdmin())
			{
				if ( $request->postData( 'submit' ) ) {
					
					$Newg = new Newg( [ 'fk_NNC' => $request->getData( 'id' ) ] );
					$this->executePutNews( $request, $Newg );
					
				}
				$this->page->addVar( 'submit', 'Valider' );
				$this->page->addVar( 'action', '' );
				$this->page->addVar( 'title', 'Edition d\'une news' );
				
				/** @var Newg $Newg */
				$Newg = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId($Newc->id());
				if (false === $Newg) {
					$this->app->httpResponse()->redirect(self::getLinkToIndex());
				}
				
				$Memberc = $this->managers->getManagerOf( 'Memberc' )->getMembercUsingId($Newg->fk_MMC());
				
				
				
				if($Newg){
					$formBuilder = new NewsFormBuilder($Newg);
					$formBuilder->build();
					
					$form = $formBuilder->form();
					
					$infos = 'Dernière édition le '.$Newg->date_edition().' par '.$Memberc->login();
					$this->page->addVar( 'infos', $infos );
					
					$this->page->addVar( 'form', $form->createView() );
				}
				
			}else{
				$this->app->httpResponse()->redirect('/news-'.$request->getData( 'id' ).'.html');
			}
			
			
		}else{ // Sinon on ajoute une novuelle news
			
			if ( $request->postData( 'submit' ) ) { // si le formulaire a été validée
				$this->executePutNews( $request, new Newg() );
			}
			else { // Sinon on construit le formulaire
				$this->page->addVar( 'title', 'Ajout d\'une news' );
				
				$formBuilder = new NewsFormBuilder( new Newg() );
				$formBuilder->build();
				
				$form = $formBuilder->form();
				
				$this->page->addVar( 'form', $form->createView() );
				$this->page->addVar( 'submit', 'Valider' );
				$this->page->addVar( 'action', '' );
				
			}
		}
	}
	
	private function executePutNews(HTTPRequest $request, Newg $Newg) {
		
		$Newg->setTitle($request->postData('title'));
		$Newg->setContent($request->postData('content'));
		$Newg->setFk_MMC($this->app()->user()->getAttribute('Memberc')->Id());
		$Newg->setFk_NNE(Newg::NNE_VALID);
		$Newg->setDate_edition(date("Y-m-d H:i:s"));
		
		$formBuilder = new NewsFormBuilder($Newg);
		$formBuilder->build();
		$form = $formBuilder->form();
		
		if(!($Newg->fk_NNC() === NULL)) // Si on édite une news
		{
			// vérifier si le contenu a changé
			$Newg_to_compare = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId($Newg->fk_NNC());
			if($Newg->isEqual($Newg_to_compare))
			{
				$this->app->user()->setFlash('Vous n\'avez pas modifier la news');
			}else{
				$form->entity()->setId($Newg_to_compare->id());
				// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
				$formHandler = new FormHandler($form, $this->managers->getManagerOf('Newg'), $request);
				
				if ($formHandler->process())
				{
					$this->app->user()->setFlash('News bien modifée !');
					$this->app->httpResponse()->redirect( '/news-'.$form->entity()->fk_NNC().'.html');
				}
			}
			
		}else{ //si on ajoute une nouvelle news
			
			// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
			$formHandler = new FormHandler($form, $this->managers->getManagerOf('Newg'), $request);
			
			if ($formHandler->process())
			{
				$this->app->user()->setFlash('News bien ajoutée !');
				$this->app->httpResponse()->redirect(  '/news-'.$form->entity()->fk_NNC().'.html' );
			}
		}
		
		$this->page->addVar('title', 'Ajout d\'une news');
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('submit', 'Valider');
		$this->page->addVar('action', '');
		
	}
	
	public function executeBuildNewsDetail(HTTPRequest $request)
	{
		
		$Newg = $this->managers->getManagerOf('Newc')->getNewsUsingNewcId($request->getData('id'));
		if ( ($Newg->id() == 0) || ($Newg == NULL) )
		{
			$this->app->httpResponse()->redirect('/');
		}
		
		// si le user est authentifié et qu'il s'agit soit d'un admin soit d'une de ses news, on lui
		if($this->app->user()->isAuthenticated() && (($this->app->user()->getAttribute('Memberc')->id() == $Newg->fk_MMC()->id())	|| $this->app->user()->getAttribute('Memberc')->isTypeAdmin())	)
		{
			
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
			$Commentc->setFk_MMC($this->app()->user()->getAttribute('Memberc')->id());
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

	public static function getLinkToIndex( ) {
		return RouterFactory::getRouter('Frontend')->getRouteFromAction('News','index')->generateHref()	;
	}
	public static function getLinkToBuildNewsDetail(Newg $Newg) {
		return RouterFactory::getRouter('Frontend')->getRouteFromAction('News','BuildNewsDetail',array('id' =>$Newg->fk_NNC()->id()) )->generateHref()	;
	}

	
	
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 15:53
 */

namespace App\Frontend\Modules\News;

use App\Filter\FilterMatchUserOrAdmin;
use App\Frontend\FrontendApplication;
use Entity\Memberc;
use Entity\Newg;
use FormBuilder\NewsFormBuilder;
use Model\CacheManagerPDO;
use Model\NewcManager;
use \OCFram\BackController;
use OCFram\Filter;
use OCFram\Filterable;
use \OCFram\HTTPRequest;
use \Entity\Commentc;
use \Entity\Newc;
use \OCFram\Form;
use OCFram\Manager;
use OCFram\ManagerCached;
use OCFram\RouterFactory;
use \OCFram\Field;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;

class NewsController extends BackController implements Filterable {
	
	/**
	 * Retourne un Filter ou une collection de filter en fonction de l'action courrante
	 *
	 * @return Filter|Filter[]|null
	 */
	public function getFilterableFilter() {
		switch ( $this->action ) {
			case "index" :
			case "BuildNewsDetail" :
			case "BuildCommentForm" :
			case "BuildSubscription" :
			case "PutCommentcJS" :
			case "GetListCommentcJS":
				return [];
			case "BuildNewsUpdate":
				$App = $this->app();
				
				/** @var Newc $Newc */
				$Newc = $this->managers->getManagerOf( 'Newc' )->getNewcUsingNewcId( $App->httpRequest()->getData( 'id' ) );
				if ( false === $Newc ) {
					$App->httpResponse()->redirect( self::getLinkToIndex() );
				}
				
				$Member = new Memberc(['id' => $Newc->fk_MMC()]);
				return [
					new FilterMatchUserOrAdmin(function() use($App, $Newc){
						$App->user()->setFlash('Vous ne pouvez pas modiifer cette news');
						$App->httpResponse()->redirect(self::getLinkToBuildNewsDetail($this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId( $Newc->id() )));
					},
						$App->user(),
						false, // FALSE : si le user ne vérifie pas le filtre, il n'a pas accès à cette action
						$Member)
				];
			case 'BuildNews' : // Si l'utilisateur courant est un visiteur, il n'a pas accès à cette action
				return [
					FrontendApplication::buildFilterGuest( $this->app() ),
				];
		}
		
		return null;
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * Affiche une liste de news
	 *
	 * TO DO: ajouter la possibilité d'afficher un nombre limité de news
	 */
	public function executeIndex( HTTPRequest $request ) {
		
		$nombreNews       = $this->app->config()->get( 'nombre_news' );
		$nombreCaracteres = $this->app->config()->get( 'nombre_caracteres' );
		
		// On ajoute une définition pour le titre.
		$this->page->addVar( 'title', 'Liste des '.$nombreNews.' dernières news' );
		
		// On récupère le manager des news.
		/** @var NewcManager $NewsManagerCached */
		$NewsManagerCached = $this->managers->getManagerOf( 'Newc' );
		
		/** @var NewcManager $NewsManagerNotCached */
		$NewsManagerNotCached = $this->managers->getManagerOf( 'Newc' , false );
		// Cette ligne, vous ne pouviez pas la deviner sachant qu'on n'a pas encore touché au modèle.
		// Contentez-vous donc d'écrire cette instruction, nous implémenterons la méthode ensuite.
		$listeNews = $NewsManagerCached->getNewsListUsingNNE( 0, $nombreNews, Newc::NNE_VALID );
		$listeNews = $NewsManagerNotCached->getNewsListUsingNNE( 0, $nombreNews, Newc::NNE_VALID );
		
		foreach ( $listeNews as $news ) {
			if ( strlen( $news->content() ) > $nombreCaracteres ) {
				$debut = substr( $news->content(), 0, $nombreCaracteres );
				$debut = substr( $debut, 0, strrpos( $debut, ' ' ) ) . '...';
				
				$news->setContent( $debut );
			}
		}
		// On ajoute la variable $listeNews à la vue.
		$this->page->addVar( 'listeNews', $listeNews );
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * Ajout ou mise à jour d'une news
	 *
	 */
	public function executeBuildNews( HTTPRequest $request ) {
		
		// on ajoute une nouvelle news
		if ( $request->postData( 'submit' ) ) { // si le formulaire a été validée
			$this->executePutNews( $request, new Newg() );
		}
		else { // Sinon on construit le formulaire
			$this->page->addVar( 'title_form', 'Ajout d\'une news' );
			$this->page->addVar( 'title', 'Ajout d\'une news' );
			$formBuilder = new NewsFormBuilder( new Newg() );
			$formBuilder->build();
			
			$form = $formBuilder->form();
			
			$this->page->addVar( 'form', $form->createView() );
			$this->page->addVar( 'submit', 'Valider' );
			$this->page->addVar( 'action', '' );
		}
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * 	Action permettant de modifier une news (accessible par l'admin ou le createur de la news)
	 */
	public function executeBuildNewsUpdate( HTTPRequest $request ) {
		// L'identifiant de la news est transmis si on veut la modifier, s'il n'y en a pas on redirige
		if ( !$request->getExists( 'id' ) ) {
			$this->app->httpResponse()->redirect404();
		}
		
			/** @var NewcManager $NewcManager */
			$NewcManager = $this->managers->getManagerOf( 'Newc' );
			/** @var Newc $Newc */
			$Newc = $NewcManager->getNewcUsingNewcId( $request->getData( 'id' ) );
			if ( false === $Newc ) { // si la newc n'existe pas
				$this->app->httpResponse()->redirect( self::getLinkToIndex() );
			}
			
			/** @var Memberc $Memberc */
			$Memberc = $this->app()->user()->getAttribute( 'Memberc' );
		
			// si le formulaire a été soumis
			if ( $request->postData( 'submit' ) ) {
				
				$Newg = new Newg( [ 'fk_NNC' => $request->getData( 'id' ) ] );
				$this->executePutNews( $request, $Newg );
			}
			
			$this->page->addVar( 'submit', 'Valider' );
			$this->page->addVar( 'action', '' );
			$this->page->addVar( 'title', 'Edition d\'une news' );
			$this->page->addVar( 'title_form', 'Edition d\'une news' );
			
			/** @var Newg $Newg */
			$Newg = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId( $Newc->id() );
			
			if ( false === $Newg ) {
				$this->app->httpResponse()->redirect( self::getLinkToIndex() );
			}
			
			$Memberc = $this->managers->getManagerOf( 'Memberc' )->getMembercUsingId( $Newg->fk_MMC() );
		
			$formBuilder = new NewsFormBuilder( $Newg );
			$formBuilder->build();
			
			$form = $formBuilder->form();
			
			$infos = 'Dernière édition le ' . $Newg->date_edition() . ' par ' . $Memberc->login();
			$this->page->addVar( 'infos', $infos );
			
			$this->page->addVar( 'form', $form->createView() );
	}
	
	/**
	 * @param HTTPRequest $request
	 * @param Newg        $Newg
	 *
	 * 	Tente d'insérer ou updater une news
	 */
	private function executePutNews( HTTPRequest $request, Newg $Newg ) {
		
		$Newg->setTitle( $request->postData( 'title' ) );
		$Newg->setContent( $request->postData( 'content' ) );
		$Newg->setFk_MMC( $this->app()->user()->getAttribute( 'Memberc' )->Id() );
		$Newg->setFk_NNE( Newg::NNE_VALID );
		$Newg->setDate_edition( date( "Y-m-d H:i:s" ) );
		
		$formBuilder = new NewsFormBuilder( $Newg );
		$formBuilder->build();
		$form = $formBuilder->form();
		
		if ( !( $Newg->fk_NNC() === null ) ) // Si on édite une news
		{
			// vérifier si le contenu a changé
			$Newg_to_compare = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId( $Newg->fk_NNC() );
			if ( $Newg->isEqual( $Newg_to_compare ) ) {
				$this->app->user()->setFlash( 'Vous n\'avez pas modifier la news' );
			}
			else {
				$form->entity()->setId( $Newg_to_compare->id() );
				// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
				$formHandler = new FormHandler( $form, $this->managers->getManagerOf( 'Newg' ), $request );
				
				if ( $formHandler->process() ) {
					$this->app->user()->setFlash( 'News bien modifée !' );
					$this->app->httpResponse()->redirect( '/news-' . $form->entity()->fk_NNC() . '.html' );
				}
			}
			$this->page->addVar( 'title', 'Edition d\'une news' );
			$this->page->addVar( 'title_form', 'Edition d\'une news' );
		}
		else { //si on ajoute une nouvelle news
			
			// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
			$formHandler = new FormHandler( $form, $this->managers->getManagerOf( 'Newg' ), $request );
			
			if ( $formHandler->process() ) {
				$this->app->user()->setFlash( 'News bien ajoutée !' );
				$this->app->httpResponse()->redirect( '/news-' . $form->entity()->fk_NNC() . '.html' );
			}
			$this->page->addVar( 'title_form', 'Ajout d\'une news' );
			$this->page->addVar( 'title', 'Ajout d\'une news' );
		}
		
		$this->page->addVar( 'form', $form->createView() );
		$this->page->addVar( 'submit', 'Valider' );
		$this->page->addVar( 'action', '' );
	}
	
	public function executeBuildNewsDetail( HTTPRequest $request ) {
		/** @var Newg|bool $Newg */
		$Newg = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId( $request->getData( 'id' ) );
		if ( false == $Newg ) {
			$this->app->httpResponse()->redirect( self::getLinkToIndex() );
		}
		
		$comments = $this->managers->getManagerOf( 'Commentc' )->getCommentcListUsingNewcId( $Newg->fk_NNC() );
		$Newg->Newc()->setMemberc($this->managers->getManagerOf( 'Memberc' )->getMembercUsingId( $Newg->Newc()->fk_MMC()));
		$this->page->addVar( 'title', $Newg->title() );
		$this->page->addVar( 'Newg', $Newg );
		$this->page->addVar( 'comments', $comments );
		
		// création du form
		$formBuilder = new CommentFormBuilder( new Commentc(), $this );
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		$this->page->addVar( 'title_form', 'Ajout d\'un commentaire' );
		$this->page->addVar( 'form', $form->createView() );
		$this->page->addVar( 'submit', 'Valider' );
		$this->page->addVar( 'action', self::getLinkToPutComment( $Newg ) );
		$this->page->addVar( 'js_action', self::getLinkToPutCommentJS( $Newg ) );
		
		// envoyer le dernier commentaire de la liste
		$Commentc_last = new Commentc( [ 'id' => 0 ] );
		
		foreach ( $comments as $Comment_temp ) {
			if ( $Comment_temp->id() > $Commentc_last->id() ) {
				$Commentc_last = $Comment_temp;
			}
		}
		
		$this->page->addVar( 'Commentc_last', $Commentc_last );
		
		
		if ( $this->app->user()->isAuthenticated() ) {
			$Memberc = $this->app->user()->getAttribute( 'Memberc' );
			if ( $Memberc->id() === $Newg->Newc()->fk_MMC() || $Memberc->isTypeAdmin() ) {
				$this->page()->addVar( 'href_edit', RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'BuildNewsUpdate', [ 'id' => $Newg[ 'Newc' ][ 'id' ] ] )
																 ->generateHref() );
			}
		}
		
		//$this->page()->addVar('href_refresh_comment_list',self::getLinkToRefreshCommentJS( $Newg ));
		$this->page()->addVar( 'href_refresh_comment_list', self::getLinkToRefreshListCommentJS( $Newg, $Commentc_last ) );
		// ##
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * Action to insert comment with php (in case js doesn't handle it)
	 */
	public function executePutCommentc( HTTPRequest $request ) {
		
		$formHandler = $this->PutCommentc( $request );
		
		$Form = $formHandler->form();
		/** @var Commentc $Commentc */
		$Commentc = $Form->entity();
		
		/** @var Newg $Newg */
		$Newg = $this->managers->getManagerOf( 'Newg' )->getNewgUsingNewgId( $Commentc->fk_NNG() );
		
		if ( $formHandler->process() ) {
			$this->app->user()->setFlash( 'Merci pour votre commentaire !' );
			$this->app->httpResponse()->redirect( self::getLinkToBuildNewsDetail( $Newg ) );
		}
		
		$comments = $this->managers->getManagerOf( 'Commentc' )->getCommentcListUsingNewcId( $Newg->fk_NNC() );
		$this->page->addVar( 'title', $Newg->title() );
		$this->page->addVar( 'Newg', $Newg );
		$this->page->addVar( 'comments', $comments );
		$this->page->addVar( 'title_form', 'Ajout d\'un commentaire' );
		$this->page->addVar( 'form', $Form->createView() );
		$this->page->addVar( 'submit', 'Valider' );
		$this->page->addVar( 'action', self::getLinkToPutComment( $Newg ) );
		$this->page->addVar( 'js_action', self::getLinkToPutCommentJS( $Newg ) );
	}
	
	
	// TO DO : Supprimer car ramplacée par GetListCommentJS, validation requise
	public function executeUpdateListCommentcJS( HTTPRequest $request ) {
		
		//sleep(32);
		
		if ( !$request->postExists( 'lastcomment' ) || !$request->getExists( 'news' ) ) {
			return;
		}
		
		// Récupérer les derniers commentaire ajoutés à l'aide du lastcomment
		/** @var Commentc $lastCommentc */
		$last_commentc_id = $request->postData( 'lastcomment' );
		$last_newc_id     = $request->getData( 'news' );
		/** @var Commentc[] $comments */
		$Comment_a = $this->managers->getManagerOf( 'Commentc' )->getLastCommentcListUsingNewcIdAndCommentcId( $last_newc_id, $last_commentc_id );
		$status    = 'valid';
		
		if ( $this->app()->user()->isAuthenticated() && $this->app()->user()->getAttribute( 'Memberc' )->isTypeAdmin() ) {
			$url_update_a = [];
			$url_delete_a = [];
			
			/** @var Commentc $Comment */
			foreach ( $Comment_a as $Comment ) {
				$url_update_a[ $Comment->id() ] = \App\Backend\Modules\News\NewsController::getLinkToUpdateComment( $Comment );
				$url_delete_a[ $Comment->id() ] = \App\Backend\Modules\News\NewsController::getLinkToClearComment( $Comment );
			}
			$this->page->addVar( 'url_update_a', $url_update_a );
			$this->page->addVar( 'url_delete_a', $url_delete_a );
		}
		
		$this->page->addVar( 'Comment_a', $Comment_a );
		$this->page->addVar( 'status', $status );
	}
	
	// nouvelle version de la fonciton permettant le refresh
	/**
	 * @param HTTPRequest $request
	 *
	 *  Fonction appelée par le script JS de refresh d'une liste de commentaire
	 */
	public function executeGetListCommentcJS( HTTPRequest $request ) {
		
		if ( !$request->getExists( 'lastcomment' ) || !$request->getExists( 'news' ) ) {
			return;
		}
		
		// Récupérer les derniers commentaire ajoutés à l'aide du lastcomment
		/** @var Commentc $lastCommentc */
		$last_commentc_id = $request->getData( 'lastcomment' );
		$last_newc_id     = $request->getData( 'news' );
		/** @var Commentc[] $comments */
		$Comment_a = $this->managers->getManagerOf( 'Commentc' )->getLastCommentcListUsingNewcIdAndCommentcId( $last_newc_id, $last_commentc_id );
		$status    = 'valid';
		
		if ( $this->app()->user()->isAuthenticated() && $this->app()->user()->getAttribute( 'Memberc' )->isTypeAdmin() ) {
			$url_update_a = [];
			$url_delete_a = [];
			
			/** @var Commentc $Comment */
			foreach ( $Comment_a as $Comment ) {
				$url_update_a[ $Comment->id() ] = \App\Backend\Modules\News\NewsController::getLinkToUpdateComment( $Comment );
				$url_delete_a[ $Comment->id() ] = \App\Backend\Modules\News\NewsController::getLinkToClearComment( $Comment );
			}
			$this->page->addVar( 'url_update_a', $url_update_a );
			$this->page->addVar( 'url_delete_a', $url_delete_a );
		}
		
		$this->page->addVar( 'Comment_a', $Comment_a );
		$this->page->addVar( 'status', $status );
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * Action to insert a comment with js
	 */
	public function executePutCommentcJS( HTTPRequest $request ) {
		sleep( mt_rand( 1, 2 ) );
		// sleep(3); /* TEST attente serveur */
		
		$formHandler = $this->PutCommentc( $request );
		
		$Form = $formHandler->form();
		/** @var Commentc $Commentc */
		$Commentc = $Form->entity();
		
		$status = 'error';
		
		if ( $formHandler->process() ) {
			// succes de l'insert
			$status = 'success';
			
			// récupérer le commentaire
			/** @var Commentc $Commentc_inserted */
			$data[ 'Comment' ] = $Commentc;
			$this->page->addVar( 'Comment', $Commentc );
			
			
			if ( $this->app->user()->isAuthenticated() && $this->app->user()->getAttribute( 'Memberc' )->isTypeAdmin() ) {
				$url_delete = \App\Backend\Modules\News\NewsController::getLinkToUpdateComment( $Commentc );
				$url_update = \App\Backend\Modules\News\NewsController::getLinkToUpdateComment( $Commentc );
				
				$data[ 'url_update' ] = $url_update;
				$data[ 'url_delete' ] = $url_delete;
				
				$this->page->addVar( 'url_update', $url_update );
				$this->page->addVar( 'url_delete', $url_delete );
			}
		}
		else {
			// Parcourir les champs pour détecter des éventuelles erreurs
			/** @var Field $Field */
			$error_a = [];
			foreach ( $Form->Fields() as $Field ) {
				if ( $Field->errorMessage() != null ) {
					$error_a[ $Field->name() ] = $Field->errorMessage();
				}
			}
			$this->page->addVar( 'error_a', $error_a );
		}
		$this->page->addVar( 'status', $status );
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * @return FormHandler
	 *
	 * Prepare an insert of Commentc with the FormHandlerr.
	 *
	 * Return a FormHandler. Just call FormHandler process to insert the commentc
	 */
	public function PutCommentc( HTTPRequest $request ) {
		$news_id = null;
		
		if ( $request->getExists( 'news' ) ) {
			$news_id = $request->getData( 'news' );
		}
		elseif ( $request->getExists( 'id' ) ) {
			$news_id = $request->getData( 'id' );
		}
		
		if ( $news_id == null ) {
			$this->app()->httpResponse()->redirect( self::getLinkToIndex() );
		}
		/** @var Newg $Newg */
		$Newg     = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId( $news_id );
		$Commentc = new Commentc( [
			'content' => $request->postData( 'content' ),
			'fk_NCE'  => Commentc::NCE_VALID,
			'date'    => date( "Y-m-d H:i:s" ),
			'fk_NNG'  => $Newg->id(),
		] );
		
		if ( $this->app()->user()->isAuthenticated() ) {
			/** @var Memberc $Memberc */
			$Memberc = $this->app()->user()->getAttribute( 'Memberc' );
			$Commentc->setFk_MMC( $Memberc->id() );
			$Commentc->setMemberc( new Memberc( [
				'id'    => $Memberc->id(),
				'login' => $Memberc->login(),
			] ) );
		}
		else {
			$Commentc->setVisitor( $request->postData( 'visitor' ) );
		}
		
		/** @var CommentFormBuilder $formBuilder */
		$formBuilder = new CommentFormBuilder( $Commentc, $this );
		$formBuilder->build();
		
		/** @var Form $form */
		$form = $formBuilder->form();
		
		// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
		/** @var FormHandler $formHandler */
		$formHandler = new FormHandler( $form, $this->managers->getManagerOf( 'Commentc' ), $request );
		
		return $formHandler;
	}
	
	/**
	 * @param HTTPRequest $request
	 *
	 * 	TO DO : offrir la possibilité aux users de modifier leur commentaire | supprimer la fonction
	 */
	public function executeBuildCommentForm( HTTPRequest $request ) {
		if ( $request->getExists( 'id' ) ) { // L'identifiant du com est transmis si on veut le modifier
			
			if ( $request->postData( 'submit' ) ) {
				
				$formHandler = $this->PutCommentc( $request );
				$Form        = $formHandler->form();
				/** @var Commentc $Commentc */
				$Commentc = $Form->entity();
				
				/** @var Newg $Newg */
				$Newg = $this->managers->getManagerOf( 'Newg' )->getNewgUsingNewgId( $Commentc->fk_NNG() );
				
				if ( $formHandler->process() ) {
					$this->app->user()->setFlash( 'Merci pour votre commentaire !' );
					$this->app->httpResponse()->redirect( self::getLinkToBuildNewsDetail( $Newg ) );
				}
			}
			else {
				$this->page->addVar( 'submit', 'Valider' );
				$this->page->addVar( 'action', '' );
				$this->page->addVar( 'title', 'Modification d\'un commentaire' );
				
				$Commentc = $this->managers->getManagerOf( 'Commentc' )->getCommentcUsingCommentcId( $request->getData( 'id' ) );
				
				if ( $Commentc ) {
					$formBuilder = new CommentFormBuilder( $Commentc, $this );
					$formBuilder->build();
					
					$form = $formBuilder->form();
					
					//$infos = 'Dernière édition le '.$Newg->date_edition().' par '.$Memberc->login();
					//$this->page->addVar( 'infos', $infos );
					
					$this->page->addVar( 'form', $form->createView() );
				}
				else {  // lien incorrect
					// redirect et message au user
					$this->app->user()->setFlash( 'Commentaire introuvable !' );
					$this->app->httpResponse()->redirect( '/' );
				}
			}
		}
		
		// redirect et message au user
		$this->app->user()->setFlash( 'Commentaire introuvable !' );
		$this->app->httpResponse()->redirect( '/' );
	}
	
	/**
	 * @return string
	 * Lien vers l'index (liste de news)
	 */
	public static function getLinkToIndex() {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'index' )->generateHref();
	}
	
	/**
	 * @param Newg $Newg
	 *
	 * @return string
	 */
	public static function getLinkToBuildNewsDetail( Newg $Newg ) {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'BuildNewsDetail', array( 'id' => $Newg->fk_NNC() ) )->generateHref();
	}
	
	/**
	 *
	 * @return string
	 */
	public static function getLinkToBuildNews() {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'BuildNews' )->generateHref();
	}
	
	/**
	 * @param Newg $Newg
	 *
	 * @return string
	 */
	public static function getLinkToPutComment( Newg $Newg ) {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'PutCommentc', array( 'news' => $Newg->fk_NNC() ) )->generateHref();
	}
	
	/**
	 * @param Newg $Newg
	 *
	 * @return string
	 */
	public static function getLinkToPutCommentJS( Newg $Newg ) {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'PutCommentcJS', array( 'news' => $Newg->fk_NNC() ) )->generateHref();
	}
	
	/**
	 * @param Newg $Newg
	 *
	 * @return string
	 */
	public static function getLinkToRefreshCommentJS( Newg $Newg ) {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'UpdateListCommentcJS', array( 'news' => $Newg->fk_NNC() ) )->generateHref();
	}
	
	/**
	 * @param Newg     $Newg
	 *
	 * @param Commentc $Commentc
	 *
	 * @return string
	 */
	public static function getLinkToRefreshListCommentJS( Newg $Newg, Commentc $Commentc ) {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'News', 'GetListCommentcJS', array(
			'news'        => $Newg->fk_NNC(),
			'lastcomment' => $Commentc->id(),
		) )->generateHref();
	}
}
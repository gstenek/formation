<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:30
 */

namespace App\Backend\Modules\News;

use Entity\Newc;
use Entity\Newg;
use FormBuilder\NewsFormBuilder;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \OCFram\FormHandler;
use FormBuilder\CommentFormBuilder;

class NewsController extends BackController
{
	public function executeIndex(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Gestion des news');
		
		$manager = $this->managers->getManagerOf('Newc');
		
		$this->page->addVar('listeNews', $manager->getNewsListUsingNNE(-1,-1,Newc::NNE_VALID));
		$this->page->addVar('nombreNews', $manager->countNewcUsingNNE(Newc::NNE_VALID));
	}
	
	public function executeBuildNews( HTTPRequest $request ) {
		
			if($request->getExists( 'id' )){ // L'identifiant de la news est transmis si on veut la modifier
				
				if ( $request->postData( 'submit' ) ) {
					
					$Newg = new Newg( [ 'fk_NNC' => $request->getData( 'id' ) ] );
					$this->executePutNews( $request, $Newg );
					
				}
				$this->page->addVar( 'submit', 'Valider' );
				$this->page->addVar( 'action', '' );
				$this->page->addVar( 'title', 'Edition d\'une news' );
				
				$Newc = $this->managers->getManagerOf( 'Newc' )->getNewcUsingNewcId( $request->getData( 'id' ) );

				$Newg = $this->managers->getManagerOf( 'Newg' )->getNewgValidUsingNewcId($Newc->id());

				$Memberc = $this->managers->getManagerOf( 'Memberc' )->getMembercUsingId($Newg->fk_MMC());
				
				
				
				if($Newg){
					$formBuilder = new NewsFormBuilder($Newg);
					$formBuilder->build();
					
					$form = $formBuilder->form();
					
					$infos = 'Dernière édition le '.$Newg->date_edition().' par '.$Memberc->login();
					$this->page->addVar( 'infos', $infos );
					
					$this->page->addVar( 'form', $form->createView() );
				}else{  // lien incorrect
					// redirect et message au user
					die('coucou l\'erreur');
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
					$this->app->httpResponse()->redirect( '/admin/' );
				}
			}
			
		}else{ //si on ajoute une nouvelle news
			
			// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
			$formHandler = new FormHandler($form, $this->managers->getManagerOf('Newg'), $request);
			
			if ($formHandler->process())
			{
				$this->app->user()->setFlash('News bien ajoutée !');
				$this->app->httpResponse()->redirect( '/admin/' );
			}
		}
		
		$this->page->addVar('title', 'Ajout d\'une news');
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('submit', 'Valider');
		$this->page->addVar('action', '');
		
	}
	
	public function executeUpdateComment(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Modification d\'un commentaire');
		
		if ($request->method() == 'POST')
		{
			$comment = new Comment([
				'id' => $request->getData('id'),
				'auteur' => $request->postData('auteur'),
				'contenu' => $request->postData('contenu')
			]);
		}
		else
		{
			$comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
		}
		
		$formBuilder = new CommentFormBuilder($comment);
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
		$formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été modifié');
			$this->app->httpResponse()->redirect('/admin/');
		}
		
		$this->page->addVar('form', $form->createView());
	}
	
	public function executeClearNews(HTTPRequest $request)
	{
		$newsId = $request->getData('id');
		
		$this->managers->getManagerOf('Newc')->updatefk_NNEOfNewcUsingNewcIdAndNNE($newsId, Newc::NNE_INVALID);
		//$this->managers->getManagerOf('Comments')->deleteFromNews($newsId);
		
		$this->app->user()->setFlash('La news a bien été supprimée !');
		
		$this->app->httpResponse()->redirect('.');
	}
	
	
	public function executeDeleteComment(HTTPRequest $request)
	{
		$this->managers->getManagerOf('Comments')->delete($request->getData('id'));
		
		$this->app->user()->setFlash('Le commentaire a bien été supprimé !');
		
		$this->app->httpResponse()->redirect('.');
	}
	
	
}
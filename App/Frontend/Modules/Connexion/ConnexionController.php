<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:28
 */

namespace App\Frontend\Modules\Connexion;

use App\Frontend\FrontendApplication;
use FormBuilder\ConnexionFormBuilder;
use \OCFram\BackController;
use OCFram\Filter;
use OCFram\Filterable;
use \OCFram\HTTPRequest;
use \Entity\Memberc;
use OCFram\RouterFactory;

class ConnexionController extends BackController implements Filterable
{
	/**
	 * Retourne un Filter ou une collection de filter en fonction de l'action courrante
	 *
	 * @return Filter|Filter[]|null
	 */
	public function getFilterableFilter() {
		switch ( $this->action ) {
			case "index" :
				return [FrontendApplication::buildFilterUser($this->app(), 'Vous êtes déjà connecté.')];
			case "logout" :
				return [FrontendApplication::buildFilterGuest($this->app())];
		}
		
		return null;
	}
	
	public function executeIndex(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Connexion');
		
		if ($request->postExists('login')) {
			$login    = $request->postData( 'login' );
			$password = $request->postData( 'password' );
			
			$manager = $this->managers->getManagerOf( 'Memberc' );
			$Memberc = $manager->getMembercUsingLogin( $login );
			
			
			if ( $Memberc && password_verify ( $password , $Memberc->password())) {
				$this->app->user()->setAuthenticated( true );
				$this->app->user()->setAttribute( 'Memberc', $Memberc );
				$this->app->httpResponse()->redirect( '.' );
			}
			else {
				$this->app->user()->setFlash( 'Le pseudo ou le mot de passe est incorrect.' );
			}
		}
		
		$formBuilder = new ConnexionFormBuilder(new Memberc());
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('submit', 'Connexion');
		$this->page->addVar('action', '');
		$this->page->addVar('title_form', 'Connexion');
	}
	
	public function executeLogout(HTTPRequest $request)
	{
		// Current user no longer authenticated
		$this->app->user()->setAuthenticated(false);
		
		// Destroy session
		session_unset();
		session_destroy();
		
		// Redirect to homepage
		$this->app->httpResponse()->redirect('/');
	}
	
	/**
	 * @return string
	 */
	public static function getLinkToIndex() {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'Connexion', 'index' )->generateHref();
	}
	
	/**
	 * @return string
	 */
	public static function getLinkToLogout() {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'Connexion', 'logout' )->generateHref();
	}
	
}
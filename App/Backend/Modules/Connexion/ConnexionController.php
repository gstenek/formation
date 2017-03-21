<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:28
 */

namespace App\Backend\Modules\Connexion;

use FormBuilder\ConnexionFormBuilder;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Memberc;

class ConnexionController extends BackController
{
	public function executeIndex(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Connexion');
		
		if ($request->postExists('login')) {
			$login    = $request->postData( 'login' );
			$password = $request->postData( 'password' );
			
			$manager = $this->managers->getManagerOf( 'Memberc' );
			$Memberc = $manager->getMembercUsingLogin( $login );
			
			if ( $Memberc && $password == $Memberc->password() ) {
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
	
}
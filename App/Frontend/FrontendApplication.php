<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 18:32
 */

namespace App\Frontend;

use App\Filter\FilterAdmin;
use App\Filter\FilterGuest;
use App\Filter\FilterUser;
use App\Frontend\Modules\Connexion\ConnexionController;
use App\Frontend\Modules\Subscription\SubscriptionController;
use App\Menu\Menu;
use App\Menu\MenuElement;
use \OCFram\Application;
use OCFram\Filter;

class FrontendApplication extends Application{
	
	public function __construct() {
		
		parent::__construct();
		
		$this->name = 'Frontend';
	}
	
	public function run() {
		
		$controller = $this->getController();
		$controller->execute();
		
		// TEST SOUS MENUS
		/*$Menu_sub = new Menu();
		$Menu_sub->addElement(new MenuElement( 'Toto', '#' ) );
		$Menu_sub->addElement(new MenuElement( 'Titi', '#' ) );*/
		
		
		// ###### Generation du menu
		$Menu = new Menu(new MenuElement('Accueil',\App\Frontend\Modules\News\NewsController::getLinkToIndex()));
		
		// TEST SOUS MENU
		//$Menu->addElement(new MenuElement('Accueil',\App\Frontend\Modules\News\NewsController::getLinkToIndex(),$Menu_sub));
		
		$Filter_a = [
			new FilterAdmin( function() use ( $Menu ) {
				
				$Menu->addElement( new MenuElement( 'Admin', \App\Backend\Modules\News\NewsController::getLinkToIndex() ) );
			}, $this->user() ),
			new FilterGuest( function() use ( $Menu ) {
				
				$Menu->addElement( [new MenuElement( 'Se connecter', ConnexionController::getLinkToIndex() ),
									new MenuElement( 'S\'inscrire', SubscriptionController::getLinkToSubscription() )] );
			}, $this->user() ),
			new FilterUser( function() use ( $Menu ) {
				
				$Menu->addElement( [new MenuElement( 'Se déconnecter', ConnexionController::getLinkToLogout() ),
									new MenuElement( 'Ajouter une News', \App\Frontend\Modules\News\NewsController::getLinkToBuildNews() ),
									new MenuElement( $this->user()->getAttribute('Memberc')->login() , '#' )] );
			}, $this->user() ),
		];
				
		/** @var Filter $Filter */
		foreach ( $Filter_a as $Filter ) {
			if($Filter->check()){
				$redirect = $Filter->redirect();
				$redirect();
			}
		}
		
		$controller->page()->addVar('menu', $Menu->create());
		
		// ######
		
		$this->httpResponse->setPage( $controller->page() );
		$this->httpResponse->send();
	}
	
	/**
	 * @param Application $App
	 *
	 * @return FilterGuest
	 */
	public static function buildFilterGuest(Application $App) {
		return new \App\Filter\FilterGuest(function() use($App) {
			$App->user()->setFlash('Vous devez être connecté pour effecuter cette action');
			$App->httpResponse()->redirect(\App\Frontend\Modules\News\NewsController::getLinkToIndex());
		},$App->user());
	}
	
	/**
	 * @param Application $App
	 * @param string      $message
	 *
	 * @return \App\Filter\FilterUser
	 */
	public static function buildFilterUser(Application $App, $message) {
		return new \App\Filter\FilterUser(function() use($App,$message) {
			$App->user()->setFlash($message);
			$App->httpResponse()->redirect(\App\Frontend\Modules\News\NewsController::getLinkToIndex());
		},$App->user());
	}
	
}
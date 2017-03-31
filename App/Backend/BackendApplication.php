<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:22
 */

namespace App\Backend;

use App\Menu\Menu;
use App\Menu\MenuElement;
use \OCFram\Application;


class BackendApplication extends Application{
	
	public function __construct() {
		
		parent::__construct();
		
		$this->name = 'Backend';
	}
	public function run()
	{
		if(!$this->user->isAuthenticated()) {
			$this->httpResponse()->redirect( '/login' );
		}
		
		if(!$this->user()->getAttribute('Memberc')->isTypeAdmin()) {
			$this->httpResponse()->redirect( '/' );
		}
		
		$controller = $this->getController();
		$controller->execute();
		
		
		// ###### Generation du menu
		$Menu = new Menu();
		$Menu->addElement(new MenuElement('Accueil',\App\Frontend\Modules\News\NewsController::getLinkToIndex()));
		$Menu->addElement( new MenuElement( 'Admin', \App\Backend\Modules\News\NewsController::getLinkToIndex() ) );
		
		$controller->page()->addVar('menu', $Menu->create());
		
		// ######
		
		
		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
			
	}
	
}
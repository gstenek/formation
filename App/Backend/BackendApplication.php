<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 16:22
 */

namespace App\Backend;

use App\Frontend\FrontendApplication;
use App\Frontend\Modules\Connexion\ConnexionController;
use App\Frontend\Modules\News\NewsController;
use \OCFram\Application;
use \Entity\Memberc;


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
		
		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
			
	}
}
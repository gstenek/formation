<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 18:32
 */

namespace App\Frontend;

use \OCFram\Application;

class FrontendApplication extends Application{
	
	public function __construct() {
		
		parent::__construct();
		
		$this->name = 'Frontend';
	}
	
	public function run() {
		$controller = $this->getController();
		$controller->execute();
		
		$this->httpResponse->setPage( $controller->page() );
		$this->httpResponse->send();
	}
}
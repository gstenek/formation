<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 11:47
 */

namespace App\Filter;


use OCFram\Filter;

class FilterAdmin extends Filter {
	
	/**
	 * @return bool
	 */
	public function check() {
		if(!$this->User()->isAuthenticated()){
			return false;
		}
		
		if(!$this->User()->getAttribute('Memberc')->isTypeAdmin()){
			return false;
		}
		
		return true;
	}
}
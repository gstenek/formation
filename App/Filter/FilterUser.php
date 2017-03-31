<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 11:16
 */

namespace App\Filter;


use OCFram\Filter;

class FilterUser extends Filter {
	
	public function check() {
		
		if($this->User()->isAuthenticated()){
			return true;
		}
		
		return false;
	}
}
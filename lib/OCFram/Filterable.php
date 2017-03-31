<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 11:07
 */

namespace OCFram;


interface Filterable {
		
	/**
	 * Retourne un Filter ou une collection de filter en fonction de l'action courrante
	 * @return Filter|Filter[]|null
	 */
	public function getFilterableFilter();
}
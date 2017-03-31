<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 14:32
 *
 *
 */

namespace App\Menu;

class Menu {
	/** @var MenuElement[] */
	protected $Element_a = [];
	
	public function __construct() {
		
	}
	
	/**
	 * @return string    Le rendu html du menu crée
	 */
	public function create() {
		$builtHtml = '<ul>';
		
		/** @var MenuElement $Element */
		foreach ( $this->Element_a as $Element ) {
			
			$builtHtml .= '<li><a href="'.$Element->url().'">'.$Element->name().'</a>';
			
			if(!null == $Element->menu_Child()){
				$builtHtml .= $Element->menu_Child()->create();
			}
			
			$builtHtml .= '</li>';
		}
		
		$builtHtml .= '</ul>';
		
		return $builtHtml;
	}
	
	/**
	 * @param MenuElement $Element
	 *
	 * @return void
	 * Ajout d'un item au menu
	 */
	public function addElement( MenuElement $Element ) {
		
		$this->Element_a[] = $Element;
		
	}
}
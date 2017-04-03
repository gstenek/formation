<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 14:32
 *
 * Générateur de menu
 */

namespace App\Menu;

class Menu {
	/** @var MenuElement[] */
	protected $Element_a = [];
	
	/**
	 * Menu constructor.
	 *
	 * @param MenuElement | MenuElement[] $Element
	 */
	public function __construct($Element = []) {
		if(! empty($Element)){
			$this->addElement($Element);
		}
	}
	
	/**
	 * @return string Le rendu html du menu crée
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
	 * @param MenuElement | MenuElement[] $Element
	 *
	 * @return void
	 * Ajout d'un item au menu
	 */
	public function addElement( $Element ) {
		
		if(!is_array($Element)){
			$Element = [$Element];
		}
		
		$this->Element_a = array_merge($this->Element_a, $Element);
	}
}
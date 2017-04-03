<?php

/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 15:01
 *
 * Item d'un menu
 */
namespace App\Menu;

class MenuElement {

	protected $name, $url, $Menu_Child;
	
	
	public function __construct($name, $url, Menu $Menu_child = null) {
		$this->setName($name);
		$this->setUrl($url);
		
		if(null != $Menu_child){
			$this->setMenuChild($Menu_child);
		}
	}
	
	
	/**
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}
	
	/**
	 * @return string
	 */
	public function url() {
		return $this->url;
	}
	
	/**
	 * @param string $url
	 */
	public function setUrl( $url ) {
		$this->url = $url;
	}
	
	/**
	 * @return Menu
	 */
	public function menu_Child() {
		return $this->Menu_Child;
	}
	
	/**
	 * @param Menu $Menu_Child Ajout d'un menu enfant à l'élement
	 */
	public function setMenuChild(Menu $Menu_Child ) {
		$this->Menu_Child = $Menu_Child;
	}
	
	
}
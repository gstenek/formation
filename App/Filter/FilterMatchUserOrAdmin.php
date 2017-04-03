<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 11:16
 */

namespace App\Filter;

use Entity\Memberc;
use OCFram\Filter;
use OCFram\User;

/**
 * Class FilterMatchUser
 *
 * @package App\Filter
 *
 * Filtre qui vérifie la correspondance d'un Member à l'utilisateur courant
 */
class FilterMatchUserOrAdmin extends Filter {
	/** @var  Memberc */
	protected $Member;
	
	/**
	 * FilterMatchUser constructor.
	 *
	 * @param callable|string $redirect_or_callback
	 * @param User            $User
	 * @param bool            $expected
	 * @param Memberc         $Member Le membre à comparer à l'utilisateur principal
	 */
	public function __construct( $redirect_or_callback, User $User, $expected = true, Memberc $Member) {
		parent::__construct( $redirect_or_callback, $User, $expected );
		$this->setMember($Member);
	}
	
	/**
	 * @return bool
	 */
	public function check() {
		
		if(!$this->User()->isAuthenticated()){
			return false;
		}
		
		/** @var Memberc $User_current */
		$User_current = $this->User()->getAttribute('Memberc');
		if(($this->Member->id() != $User_current->id()) && !$User_current->isTypeAdmin()){
			return false;
		}
		return true;
	}
	
	/**
	 * @param mixed $Member
	 */
	public function setMember(Memberc $Member ) {
		$this->Member = $Member;
	}
}
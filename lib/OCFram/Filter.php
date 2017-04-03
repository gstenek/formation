<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 31/03/2017
 * Time: 11:07
 */

namespace OCFram;


abstract class Filter {
	
	
	protected $redirect;
	protected $User;
	protected $expected;
	
	/**
	 * Filter constructor.
	 *
	 * @param string|callable $redirect_or_callback Url de redirection ou fonction de callback
	 * @param User            $User
	 * @param bool            $expected résultat souhaité pour le check du filtre
	 */
	public function __construct($redirect_or_callback, User $User, $expected = true)
	{
		$this->setRedirect($redirect_or_callback);
		$this->setUser($User);
		$this->setExpected($expected);
	}
	
	/**
	 * @return mixed
	 */
	public function expected() {
		return $this->expected;
	}
	
	/**
	 * @param mixed $expected
	 */
	public function setExpected($expected ) {
		$this->expected = $expected;
	}
	
	/**
	 * @return mixed
	 */
	public function redirect() {
		return $this->redirect;
	}
	
	/**
	 * @param string|callable $redirect Url de redirection ou fonction de callback
	 */
	public function setRedirect( $redirect ) {
		$this->redirect = $redirect;
	}
	
	/**
	 * @param User $User
	 */
	public function setUser(User $User ) {
		$this->User = $User;
	}
	
	/**
	 * @return User
	 */
	public function User() {
		return $this->User;
	}
	
	/**
	 * @return bool
	 */
	abstract public function check();
	
}
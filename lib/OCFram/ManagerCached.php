<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 03/04/2017
 * Time: 18:25
 */

namespace OCFram;

/**
 * Class ManagerCached
 * Used when you want to avoid duplicate request of data
 * @package OCFram
 */
class ManagerCached extends Manager {
	protected $manager;
	protected $result;
	
	public function __construct( Manager $Manager ) {
		$this->manager   = $Manager;
		$this->result    = [];
	}
	
	/**
	 * @param $function_name
	 * @param $arguments
	 *
	 * @return mixed
	 *
	 * Called when the function from a Manager is called
	 */
	public function __call( $function_name, $arguments ) {
		$key = $function_name . '/' . serialize( $arguments );
		if ( !array_key_exists( $key, $this->result ) ) {
			$this->result[ $key ] = call_user_func_array( [
				$this->manager,
				$function_name,
			], $arguments );
		}
		
		return $this->result[ $key ];
	}
	
	/**
	 * Fonction pour vider le tabelau des resultats conservés en cache
	 */
	public function clearCache() {
		$this->result = [];
	}
}
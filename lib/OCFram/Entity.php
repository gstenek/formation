<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:46
 *
 * Base class of entities.
 *
 */

namespace OCFram;



abstract class Entity implements \ArrayAccess, \JsonSerializable {
	
	// Utilisation du trait Hydrator pour que nos entités puissent être hydratées
	use Hydrator;
	
	const TAGS_ALLOWED = '';
	
	protected $erreurs = [],
		$id,
		$References = [];
	
	public function __construct(array $donnees = [])
	{
		if (!empty($donnees))
		{
			$this->hydrate($donnees);
		}
	}
	
	public function isNew()
	{
		return empty($this->id);
	}
	
	public function erreurs()
	{
		return $this->erreurs;
	}
	
	public function id()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = (int) $id;
	}
	
	public function offsetGet($var)
	{
		if (isset($this->$var) && is_callable([$this, $var]))
		{
			return $this->$var();
		}
	}
	
	public function offsetSet($var, $value)
	{
		$method = 'set'.ucfirst($var);
		
		if (isset($this->$var) && is_callable([$this, $method]))
		{
			$this->$method($value);
		}
	}
	
	public function offsetExists($var)
	{
		return isset($this->$var) && is_callable([$this, $var]);
	}
	
	public function offsetUnset($var)
	{
		throw new \Exception('Impossible de supprimer une quelconque valeur');
	}
	
	/**
	 * @param $name
	 *
	 * @return null|Entity
	 * @internal param Entity $Entity
	 *
	 */
	public function References($name) {
		return isset($this->References[$name])?$this->References[$name]:null;
	}
	
	/**
	 * @param Entity $Entity
	 *
	 * @param        $name
	 *
	 * @internal param Entity $Entity
	 */
	public function setReferences( Entity $Entity, $name ) {
		$this->References[$name] = $Entity;
	}
	
	
	
	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->jsonSerializeCustom();
	}
	
	protected function jsonSerializeCustom(array $exclude_column_a = []) {
		
		$exclude_column_a = array_merge($exclude_column_a,['References']);
		
		$ReflexionClass       = new \ReflectionClass( static::class );
		$ReflectionProperty_a = $ReflexionClass->getProperties();
		
		$return = [];
		
		foreach ( $ReflectionProperty_a as $ReflectionProperty ) {
			if (!in_array($ReflectionProperty->getName(),$exclude_column_a)) {
				$return[ $ReflectionProperty->getName() ] = $this->{$ReflectionProperty->getName()}();
			}
		}
		
		foreach( $this->References as $ref_name=>$value) {
			if (!in_array($ref_name,$exclude_column_a)) {
				$return[ $ref_name ] = $this->References($ref_name);
			}
		}
		
		return $return;
	}
	
}
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


abstract class Entity implements \ArrayAccess {
	
	// Utilisation du trait Hydrator pour que nos entités puissent être hydratées
	use Hydrator;
	
	protected $erreurs = [],
		$id;
	
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
}
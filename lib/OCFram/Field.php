<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:11
 */

namespace OCFram;

abstract class Field
{
	const NAME_DIV_ERROR = 'error';
	
	use Hydrator;
	
	protected $errorMessage;
	protected $label;
	protected $name;
	protected $validators = [];
	protected $value;
	protected $type;
	
	public function __construct(array $options = [])
	{
		if (!empty($options))
		{
			$this->hydrate($options);
		}
	}
	
	/**
	 * @return string
	 */
	public function buildWidget(){
		return '<div class="'.self::NAME_DIV_ERROR.'-'.static::name().'"></div>';
		
	}
	
	public function isValid()
	{
		foreach ($this->validators as $validator)
		{
			if (!$validator->isValid($this->value))
			{
				$this->errorMessage = $validator->errorMessage();
				return false;
			}
		}
		
		return true;
	}
	
	public function label()
	{
		return $this->label;
	}
	
	public function length()
	{
		return $this->length;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function validators()
	{
		return $this->validators;
	}
	
	public function value()
	{
		return $this->value;
	}
	
	public function type()
	{
		return $this->type;
	}
	
	public function setLabel($label)
	{
		if (is_string($label))
		{
			$this->label = $label;
		}
	}
	
	public function setLength($length)
	{
		$length = (int) $length;
		
		if ($length > 0)
		{
			$this->length = $length;
		}
	}
	
	public function setName($name)
	{
		if (is_string($name))
		{
			$this->name = $name;
		}
	}
	
	public function setType($type)
	{
		if (is_string($type))
		{
			$this->type = $type;
		}
	}
	
	public function setValidators(array $validators)
	{
		foreach ($validators as $validator)
		{
			if ($validator instanceof Validator && !in_array($validator, $this->validators))
			{
				$this->validators[] = $validator;
			}
		}
	}
	
	public function setValue($value)
	{
		if (is_string($value))
		{
			$this->value = $value;
		}
	}
	
	/**
	 * @return mixed
	 */
	public function errorMessage() {
		return $this->errorMessage;
	}
}
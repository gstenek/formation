<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:14
 */

namespace OCFram;

class StringField extends Field
{
	protected $maxLength;
	
	public function buildWidget()
	{
		$widget = '';
		if($this->type == '')$this->type = "text";
		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}
		
		$widget .= '<label>'.$this->label.'</label><input type="'.$this->type.'" name="'.$this->name.'"';
		
		if (!empty($this->value))
		{
			$widget .= ' value="'.htmlspecialchars($this->value).'"';
		}
		
		if (!empty($this->maxLength))
		{
			$widget .= ' maxlength="'.$this->maxLength.'"';
		}
		
		return $widget .= ' />';
	}
	
	public function setMaxLength($maxLength)
	{
		$maxLength = (int) $maxLength;
		
		if ($maxLength > 0)
		{
			$this->maxLength = $maxLength;
		}
		else
		{
			throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
		}
	}
}
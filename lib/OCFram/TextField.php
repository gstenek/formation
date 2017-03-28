<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:15
 */

namespace OCFram;

class TextField extends Field
{
	protected $cols;
	protected $rows;
	
	/**
	 * @return string
	 */
	public function buildWidget()
	{
		$widget = '';
		
		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}
		
		$widget .= '<label>'.$this->label.'</label><textarea name="'.$this->name.'"';
		
		if (!empty($this->cols))
		{
			$widget .= ' cols="'.$this->cols.'"';
		}
		
		if (!empty($this->rows))
		{
			$widget .= ' rows="'.$this->rows.'"';
		}
		
		$widget .= '>';
		
		if (!empty($this->value))
		{
			$widget .= htmlspecialchars($this->value);
		}
		
		$widget.= '</textarea>';
		
		return $widget.parent::buildWidget();
	}
	
	public function setCols($cols)
	{
		$cols = (int) $cols;
		
		if ($cols > 0)
		{
			$this->cols = $cols;
		}
	}
	
	public function setRows($rows)
	{
		$rows = (int) $rows;
		
		if ($rows > 0)
		{
			$this->rows = $rows;
		}
	}
}
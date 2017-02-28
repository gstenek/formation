<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:08
 *
 * Get the application the component belongs to
 */

namespace OCFram;

abstract class ApplicationComponent
{
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	public function app()
	{
		return $this->app;
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 27/02/2017
 * Time: 16:55
 *
 * Generates the view from the data sent by the controller with the corresponding layout
 */

namespace OCFram;

class Page extends ApplicationComponent
{
	const DEFAULT_TYPE_VIEW = 'php';
		
	protected $contentFile;
	protected $vars = [];
	
	protected $typeView = Page::DEFAULT_TYPE_VIEW;
		
	public function addVar($var, $value)
	{
		if (!is_string($var) || is_numeric($var) || empty($var))
		{
			throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
		}
		
		$this->vars[$var] = $value;
	}
	
	public function getGeneratedPage()
	{
		if (!file_exists($this->contentFile))
		{
			throw new \RuntimeException('La vue spécifiée n\'existe pas');
		}
		
		
		$user = $this->app->user();
		
		extract($this->vars);
		
		ob_start();
		require $this->contentFile;
		
		// si le type de vue demandé est php on inclue le template de base
		if('php' == $this->typeView)
		{
			$content = ob_get_clean();
			
			ob_start();
			require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
		}
		
		return ob_get_clean();
	}
	
	public function setContentFile($contentFile)
	{
		if (!is_string($contentFile) || empty($contentFile))
		{
			throw new \InvalidArgumentException('La vue spécifiée est invalide');
		}
		
		$this->contentFile = $contentFile;
	}
	
	/**
	 * @param string $typeView
	 */
	public function setTypeView( $typeView ) {
		$this->typeView = $typeView;
	}
}
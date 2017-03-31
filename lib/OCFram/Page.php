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
	
	protected $contentFile;
	protected $vars = [];
			
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
		switch($format = $this->getReturnFormat()) {
			case 'html' :
				return $this->getGeneratedPageHTML();
				break;
			case 'json' :
				return $this->getGeneratedPageJSON();
				break;
			default :
				throw new \Exception('Format de retour non géré : '.$format);
		}
	}
	
	protected function getGeneratedPageHTML() {
		$user = $this->app->user();
		
		extract($this->vars);
		
		ob_start();
		require $this->contentFile;
		
		$content = ob_get_clean();
		
		ob_start();
		require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
		
		return ob_get_clean();
	}
	
	protected function getGeneratedPageJSON() {
		$this->app()->httpResponse()->addHeader('Content-Type: application/json;charset=utf-8');
		
		extract($this->vars);
		
		$content = require $this->contentFile;
						
		return json_encode(require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.json.php');
	}
	
	private function getReturnFormat() {
		if (!function_exists('apache_request_headers')) {
			return 'html';
		}
		$header_accept = apache_request_headers()['Accept'];
		if(preg_match('$application/json$',$header_accept)) {
			return 'json';
		}
		
		return 'html';

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
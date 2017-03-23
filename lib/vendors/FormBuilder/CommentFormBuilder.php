<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:54
 */

namespace FormBuilder;

use OCFram\BackController;
use OCFram\Entity;
use \OCFram\FormBuilder;
use OCFram\NotExistValidator;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class CommentFormBuilder extends FormBuilder
{
	protected $Controller;
	
	public function __construct( Entity $Entity, BackController $Controller) {
		parent::__construct( $Entity );
		
		$this->setController($Controller);
	}
	
	public function build()
	{
		$this->form->add(new TextField([
					   'label' => 'Contenu',
					   'name' => 'content',
					   'rows' => 7,
					   'cols' => 50,
					   'validators' => [
						   new NotNullValidator('Merci de spécifier votre commentaire'),
					   ],
				   ]));
		
		if(!$this->Controller->app()->user()->isAuthenticated()){
			$visitor = new StringField([
				'label' => 'Auteur',
				'name' => 'visitor',
				'maxLength' => 50,
				'validators' => [
					new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
					new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
					new NotExistValidator('Merci d\'utiliser un nom différent d\'un utilisateur existant',$this->Controller->managers()->getManagerOf('Memberc'),'getMembercUsingLogin')
				],
			]);
			$this->form->add($visitor);
		}
	}
	
	/**
	 * @param BackController $Controller $Controller
	 */
	public function setController(BackController  $Controller) {
		$this->Controller = $Controller;
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 14:53
 */

namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class ConnexionFormBuilder extends FormBuilder
{
	public function build()
	{
		$this->form->add(new StringField([
			'label' => 'Pseudo',
			'name' => 'login',
			'maxLength' => 50,
			'validators' => [
				new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
				new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
			],
		]))
				   ->add(new StringField([
					   'label' => 'Mot de passe',
					   'name' => 'password',
					   'type' => 'password',
					   'maxLength' => 200,
					   'validators' => [
						   new MaxLengthValidator('L\'auteur spécifié est trop long (200 caractères maximum)', 50),
						   new NotNullValidator('Merci de spécifier votre commentaire'),
					   ],
				   ]));
	}
}
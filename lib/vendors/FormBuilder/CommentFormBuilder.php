<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 28/02/2017
 * Time: 18:54
 */

namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class CommentFormBuilder extends FormBuilder
{
	public function build()
	{
		$this->form->add(new StringField([
			'label' => 'Auteur',
			'name' => 'auteur',
			'maxLength' => 50,
			'validators' => [
				new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
				new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
			],
		]))
				   ->add(new TextField([
					   'label' => 'Contenu',
					   'name' => 'contenu',
					   'rows' => 7,
					   'cols' => 50,
					   'validators' => [
						   new NotNullValidator('Merci de spécifier votre commentaire'),
					   ],
				   ]));
	}
}
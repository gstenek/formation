<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 14:54
 */

namespace FormBuilder;

use Model\MembercManager;
use OCFram\BackController;
use OCFram\EmailValidator;
use OCFram\Entity;
use OCFram\Form;
use \OCFram\FormBuilder;
use OCFram\NotExistValidator;
use \OCFram\StringField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\ConfirmValidator;
use OCFram\StringValidator;

class SubscriptionFormBuilder extends FormBuilder
{
	protected $Controller;
	
	public function __construct( Entity $entity, BackController $controller ) {
		parent::__construct( $entity );
		
		$this->setController($controller);
	}
	
	public function setController( BackController $controller) {
		$this->Controller = $controller;
	}
	
	public function build()
	{
	$this->form->add(new StringField([
		'label' => 'Prénom',
		'name' => 'name',
		'maxLength' => 50,
		'validators' => [
			new MaxLengthValidator('Le prénom du membre spécifié est trop long (50 caractères maximum)', 50),
			new NotNullValidator('Merci de spécifier votre prénom'),
		],
	]))
		
		->add(new StringField([
			'label' => 'Nom',
			'name' => 'surname',
			'maxLength' => 50,
			'validators' => [
				new MaxLengthValidator('Le nom du membre spécifié est trop long (50 caractères maximum)', 50),
				new NotNullValidator('Merci de spécifier votre nom'),
			],
		]))
		
			->add($email = new StringField([
				'label' => 'Adresse Email',
				'name' => 'email',
				'maxLength' => 100,
				'validators' => [
					new MaxLengthValidator('Le mail du membre spécifié est trop long (100 caractères maximum)', 100),
					new NotNullValidator('Merci de spécifier votre email'),
					new NotExistValidator('Le mail entré est déjà existant',$this->Controller->managers()->getManagerOf('Memberc'),'getMembercUsingEmail'),
					new EmailValidator('Merci de spécifier un email valide'),
				],
			]))
		
				->add(new StringField([
					'label' => 'Confirmation de l\'adresse Email',
					'name' => 'email_confirm',
					'maxLength' => 100,
					'validators' => [
						new MaxLengthValidator('Le mail du membre spécifié est trop long (100 caractères maximum)', 100),
						new ConfirmValidator('Votre confirmation d\'email ne correspond pas à celle entré précédement',$email),// passer le field
						new NotNullValidator('Merci de spécifier votre email'),
						new EmailValidator('Merci de spécifier un email valide')
					],
				]))
			
					->add(new StringField([
						'label' => 'Pseudo',
						'name' => 'login',
						'maxLength' => 50,
						'validators' => [
							new MaxLengthValidator('Le pseudo du membre spécifié est trop long (50 caractères maximum)', 50),
							new NotNullValidator('Merci de spécifier le pseudo du membre'),
							new NotExistValidator('Le login entré est déjà existant',$this->Controller->managers()->getManagerOf('Memberc'),'getMembercUsingLogin'),
							new StringValidator('La pseudo entré contient des caractères invalides', array('<','$','>','/','^','€'))
						],
					]))
					
					   ->add($password = new StringField([
						   'label' => 'Mot de passe',
						   'name' => 'password',
						   'type' => 'password',
						   'maxLength' => 200,
						   'validators' => [
							   new MaxLengthValidator('Le mot de passe du membre  est trop long (200 caractères maximum)', 200),
							   new NotNullValidator('Merci de spécifier votre mot de passe'),
						   ],
					   ]))
							->add(new StringField([
								'label' => 'Confirmation mot de passe',
								'name' => 'password_confirm',
								'type' => 'password',
								'maxLength' => 200,
								'validators' => [
									new MaxLengthValidator('La confirmation mot de passe du membre  est trop long (200 caractères maximum)', 200),
									new NotNullValidator('Merci de confirmer votre mot de passe'),
									new ConfirmValidator('Votre confirmation de mot de passe ne correspond pas à celui entré précédement',$password)
								],
							]));
		
	}
}
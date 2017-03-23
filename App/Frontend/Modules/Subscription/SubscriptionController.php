<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 16:32
 */

namespace App\Frontend\Modules\Subscription;

use FormBuilder\SubscriptionFormBuilder;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Memberc;
use \OCFram\FormHandler;

class SubscriptionController extends BackController
{
	public function executeBuildSubscription(HTTPRequest $request){
		
		if($request->postData('submit')) {
			$this->executePutMember($request);
		}else{
			$this->page->addVar('title', 'Subscription');
			
			$this->page->addVar('action', 'PutMember');
			
			$formBuilder = new SubscriptionFormBuilder(new Memberc(),$this);
			$formBuilder->build();
			
			$form = $formBuilder->form();
			
			$this->page->addVar('form', $form->createView());
			$this->page->addVar('submit', 'Inscription');
			$this->page->addVar('action', '');
		}
		
	}
	
	public function executePutMember(HTTPRequest $request){
		
		$Memberc = new Memberc([
			'login' => $request->postData('login'),
			'email' => $request->postData('email'),
			'name' => $request->postData('name'),
			'surname' => $request->postData('surname'),
			'password' => $request->postData('password'),
			'dateBirth' => date("Y-m-d"),
			'dateInscription' => date("Y-m-d H:i:s"),
			'fk_MMY' => Memberc::MMY_BASIC,
			'fk_MME' => Memberc::MME_VALID
		]);
		
		$formBuilder = new SubscriptionFormBuilder($Memberc,$this);
		$formBuilder->build();
		$form = $formBuilder->form();
		
		// On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
		$formHandler = new FormHandler($form, $this->managers->getManagerOf('Memberc'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Bienvenue !');
			$this->app->httpResponse()->redirect( '/admin/' );
		}
		$this->page->addVar('title', 'Subscription');
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('submit', 'Inscription');
		$this->page->addVar('action', '');
	}
}
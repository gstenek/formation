<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 16:32
 */

namespace App\Frontend\Modules\Subscription;

use App\Frontend\FrontendApplication;
use FormBuilder\SubscriptionFormBuilder;
use \OCFram\BackController;
use OCFram\Filter;
use OCFram\Filterable;
use \OCFram\HTTPRequest;
use \Entity\Memberc;
use \OCFram\FormHandler;
use OCFram\RouterFactory;

class SubscriptionController extends BackController implements Filterable
{
	/**
	 * Retourne un Filter ou une collection de filter en fonction de l'action courrante
	 *
	 * @return Filter|Filter[]|null
	 */
	public function getFilterableFilter() {
		switch ( $this->action ) {
			case "BuildSubscription" :
				return [FrontendApplication::buildFilterUser($this->app(), 'Vous êtes déjà connecté.')];
		}
		
		return null;
	}
	
	public function executeBuildSubscription(HTTPRequest $request){
		
		if($request->postData('submit')) {
			$this->executePutMember($request);
		}else{
			$this->page->addVar('title', 'Subscription');
			$this->page->addVar('title_form', 'Subscription');
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
			$this->app->user()->setAuthenticated( true );
			$this->app->user()->setAttribute('Memberc',$form->entity());
			$this->app->httpResponse()->redirect( '/admin/' );
		}
		$this->page->addVar('title', 'Subscription');
		$this->page->addVar('title_form', 'Subscription');
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('submit', 'Inscription');
		$this->page->addVar('action', '');
	}
	
	/**
	 * @return string
	 */
	public static function getLinkToSubscription() {
		return RouterFactory::getRouter( 'Frontend' )->getRouteFromAction( 'Subscription', 'BuildSubscription' )->generateHref();
	}
	
}